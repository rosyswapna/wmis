<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Services\PaymentListService;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Hospital;
use App\Models\PaymentInvoice;
use App\Models\InvoiceStatus;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;


class PaymentController extends Controller
{
    protected PaymentListService $paymentListService;

    public function __construct(PaymentListService $paymentListService)
    {
        $this->paymentListService = $paymentListService;
    }

    /**
     * Display payment list.
     */
    public function index(Request  $request)
    {
        
        $clients = Client::orderBy('name')->get();

        $filters = $request->only([
            'client_id',
            'date_from',
            'date_to',
        ]);         
        $payments = $this->paymentListService
            ->query($filters)
            ->paginate(10)
            ->withQueryString(); 

        
        return view('payment.index', compact('payments','clients'));
    }

    /**
     * Show create invoice form.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();

        return view('payment.create', compact(
            'clients'
        ));
    }

    /**
     * Store payment.
     */
    public function store(Request $request)
    {
        $validations = [
            'payment_date' => ['required', 'date'],
            'client_id' => [
                'required',
                'exists:client,id',
            ], 
            'payment_method' => [
                'required',
            ],  
            'notes' => [
                'nullable',
                'string',
                'regex:/^[A-Za-z]/',
            ],      
            'amounts' => [
                'required', 'array'
            ],
            'amounts.*' => [
                'numeric', 'gt:0'
            ],           
        ];   
        $reference_number = $this->generateReferenceNumber();

        $validator = Validator::make($request->all(), $validations);
        if ($validator->fails()) {
            return redirect()
                ->route('payments.create')
                ->withErrors($validator)
                ->withInput();        
        }else{
            $validated = $validator->validated();  
            $validated['created_by'] = auth()->id(); 
            $validated['reference_number'] = $reference_number; 
            
            DB::transaction(function () use ($validated) {

                $payment = Payment::create([
                    'payment_date' => $validated['payment_date'],
                    'payment_method' => $validated['payment_method'],
                    'reference_number' => $validated['reference_number'], 
                    'notes' => $validated['notes'],                    
                    'client_id' => $validated['client_id'], 
                    'created_by' => $validated['created_by'],
                ]);
                
                $invoiceStatusProcessed = InvoiceStatus::where('name', 'Processed')->first()->id;
                $total_paid = 0;
                foreach ($validated['amounts'] as $invoiceId=>$amount) {
                   PaymentInvoice::create([
                        'payment_id' => $payment->id,
                        'invoice_id' => $invoiceId,
                        'amount' => $amount,
                    ]);
                    $total_paid += $amount;

                    $invoice = Invoice::findOrFail($invoiceId);
                    $alreadyPaid = $invoice->payments()->sum('payment_invoice.amount')?? 0;
                    $newBalance = $invoice->total - $alreadyPaid;

                    $invoice->update([
                        'payment_status' => ($newBalance == 0)? 'Paid': 'Partially Paid',
                        'status_id' => $invoiceStatusProcessed
                    ]);
                }
                $payment->update(['total_paid'=>$total_paid]);               

            });

            return redirect()
                ->route('payments')
                ->with('success', 'Payment created successfully.');
        }
    }

    public function getUnpaidInvoices($clientId)
    {
        try{
            $invoices = Invoice::where('client_id', $clientId)
                ->whereHas('status', function ($query) {
                    $query->whereIn('name', [
                        'New Invoice',
                        'Processed',
                    ]);
                })
                ->whereIn('payment_status',['Unpaid','Partially Paid'])
                ->orderBy('invoice_date')
                ->get()
                ->map(function ($invoice) {
                    $paid = $invoice->payments()->sum('payment_invoice.amount')?? 0;
                    $invoice_number = $invoice->invoice_number_full();
                    return [
                        'id' => $invoice->id,
                        'invoice_number' => $invoice_number,
                        'invoice_date' => $invoice->invoice_date->format('d-m-Y'),
                        'total' => $invoice->total,
                        'paid' => $paid,
                        'balance' => $invoice->total - $paid,
                    ];
                })
                ->filter(fn ($invoice) => $invoice['balance'] > 0)
                ->values();

            //dd($invoices); 
        } catch (\Exception $e) {
            //dd($e->getMessage(), $e->getTraceAsString());
            $invoices = [];
        }

        return response()->json($invoices);
    }

    /**
     * Export payments.
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'client_id',
            'date_from',
            'date_to',
        ]);

        // Use the same query logic as invoice listing
        $query = $this->paymentListService->query($filters);

        $reportHeaders = [
            'Reference Number',
            'Payment Date',
            'Client',
            'Total Paid',
        ];

        $reportDataKeys = [
            'reference_number',
            'payment_date',
            'client.name',
            'total_paid',
        ];

        $fileName = 'payments-' . now()->format('Y-m-d-His') . '.xlsx';

        $filePath = 'export/payments/' . $fileName;

        Excel::store(
            new PaymentExport(
                $query,
                $reportHeaders,
                $reportDataKeys
            ),
            $filePath
        );

        return Storage::download(
            $filePath,
            $fileName
        );
    }

    /**
     * Print payment.
     */
    public function print($id)
    {

        $payment = Payment::findOrFail($id);
        $payment->load([
            'client',
            'createdBy',
            'invoices',
        ]);

        $hospital = Hospital::with([
            'country',
            'state',
            'city',
        ])->first();

        $pdf = Pdf::loadView('payment.print', [
            'payment' => $payment,
            'hospital' => $hospital,
        ]);

        return $pdf->stream(
            'receipt-' . $payment->reference_number . '.pdf'
        );
    }

    /**
     * Generate payment reference number.
     */
    private function generateReferenceNumber(): string
    {
        $year = now()->year;

        $lastReference = DB::table('payment')
            ->where('reference_number', 'like', "R/%/%")
            ->orderByDesc('id')
            ->value('reference_number');

        if ($lastReference) {
            $parts = explode('-', $lastReference);

            // R / 001 / 2026
            $lastNumber = (int) $parts[1];

            // Reset numbering when year changes
            if ((int) $parts[2] === $year) {
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
        } else {
            $nextNumber = 1;
        }

        return 'R/' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '/' . $year;
    }
}

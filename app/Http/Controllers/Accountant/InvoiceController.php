<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Service;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display invoice list.
     */
    public function index()
    {
        $invoices = Invoice::with('client')
            ->latest('invoice_date')
            ->paginate(10);

        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show create invoice form.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();

        $services = Service::orderBy('name')->get();

        return view('invoice.create', compact(
            'clients',
            'services'
        ));
    }

    /**
     * Store invoice.
     */
    public function store(Request $request)
    {
        $validations = [
            'invoice_date' => ['required', 'date'],
            'client_id' => [
                'required',
                'exists:client,id',
            ],
            'service_id' => [
                'required',
                'exists:service,id',
            ],
            'unit_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],   
            'due_date' => [
                'nullable',
                'date',
            ],            
            'vat' => [
                'nullable',
                'numeric',
                'min:0',
            ],            
            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],            
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.worker_name' => [
                'required',
                'string',
                'max:255',
            ],            
        ];        
        $service = Service::findOrFail($request->service_id);                
        if (!$service->auto_invoice_number) {
            $validations['invoice_number'] = [
                    Rule::unique('invoice', 'invoice_number')
                    ->where(function ($query) {
                        $query->whereExists(function ($subQuery) {
                            $subQuery->selectRaw('1')
                                ->from('service')
                                ->whereColumn('service.id', 'invoice.service_id')
                                ->where('service.auto_invoice_number', 0);
                        });
                    }),
            ]; 
            $invoice_number = $request->invoice_number;             
        }else{        
            $invoicePrefix = Hospital::pluck('invoice_number_prefix')->first();
            $invoice_number = $this->generateInvoiceNumber();
            $invoiceSuffix = now()->format('Y');
        }

        $validator = Validator::make($request->all(), $validations);
        if ($validator->fails()) {
            return redirect()
                ->route('invoices.create')
                ->withErrors($validator)
                ->withInput();        
        }else{
            $validated = $validator->validated();            
            
            DB::transaction(function () use ($validated, $invoice_number) {            
                
                $discount = $validated['discount'] ?? 0;
                $quantity = count($validated['items']);
                $unit_price = $validated['unit_price'];
                $net_amount = $unit_price * $quantity;
                $vat = $net_amount * 5/100;
                $total = ($net_amount + $vat) - $discount;
                $statusId = InvoiceStatus::where('name', 'New Invoice')->value('id');

                $invoice = Invoice::create([
                    'invoice_date' => $validated['invoice_date'],
                    'invoice_number' => $invoice_number,
                    'invoice_number_prefix' => $invoicePrefix ?? null,
                    'invoice_number_suffix' => $invoiceSuffix ?? null,
                    'due_date' => $validated['due_date'] ?? null,
                    'payment_status' => 'Unpaid',
                    'client_id' => $validated['client_id'],
                    'service_id' => $validated['service_id'],
                    'unit_price' => $unit_price,
                    'quantity' => $quantity,
                    'net_amount' =>$net_amount,
                    'vat' => $vat,
                    'discount' => $discount,
                    'total' => $total,
                    'status_id' => $statusId,
                ]);

                foreach ($validated['items'] as $item) {
                    $invoice->items()->create([
                        'worker_name' => $item['worker_name'],                    
                    ]);
                }
            });

            return redirect()
                ->route('invoices')
                ->with('success', 'Invoice created successfully.');
        }
    }

    /**
     * Display invoice.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with([
            'client',
            'service',
        ])->findOrFail($id);

        return view(
            'invoices.show',
            compact('invoice')
        );
    }

    /**
     * Print Invoice
     */
    public function print($id)
    {
        $invoice = Invoice::findOrFail($id);

        $hospital = Hospital::with([
            'country',
            'state',
            'city',
        ])->first();

        $invoice->load([
            'client',
            'service',
            'items',
        ]);

        $pdf = Pdf::loadView('invoice.print', [
        'invoice' => $invoice,
        'hospital' => $hospital
        ]);

        return $pdf->stream(
            'invoice-' . $invoice->invoice_number . '.pdf'
        );
    }

    /**
     * Show edit invoice form.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::with('items')
            ->findOrFail($id);

        $clients = Client::orderBy('name')->get();

        $services = Service::orderBy('name')->get();

        return view(
            'invoice.edit',
            compact(
                'invoice',
                'clients',
                'services'
            )
        );
    }

    /**
     * Update invoice.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'invoice_date' => ['required', 'date'],           
            'due_date' => [
                'nullable',
                'date',
            ],
            'unit_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'vat' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],            
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.worker_name' => [
                'required',
                'string',
                'max:255',
            ],
            
        ]);

        DB::transaction(function () use (
            $validated,
            $invoice
        ) {

            $discount = $validated['discount'] ?? 0;
            $quantity = count($validated['items']);
            $unit_price = $validated['unit_price'];
            $net_amount = $unit_price * $quantity;
            $vat = $net_amount * 5/100;
            $total = ($net_amount + $vat) - $discount;

            $updateData = [
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,
                'unit_price' => $unit_price,
                'quantity' => $quantity,
                'net_amount' =>$net_amount,
                'vat' => $vat,
                'discount' => $discount,
                'total' => $total,
            ];
            if($invoice->status->name == 'Draft'){
                $updateData['status_id'] = InvoiceStatus::where('name', 'New Invoice')->value('id');
            }            

            $invoice->update($updateData);

            // Replace existing items
            $invoice->items()->delete();
            foreach ($validated['items'] as $item) {              
                $invoice->items()->create([
                    'worker_name' =>
                        $item['worker_name'],
                ]);
            }
        });

        return redirect()
            ->route(
                'invoices'
            )
            ->with(
                'success',
                'Invoice updated successfully.'
            );
    }

    /**
     * Delete invoice.
     */
    public function cancel(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $cancelledStatus = InvoiceStatus::where('name', 'Cancelled')->firstOrFail();
        $invoice->update([
            'status_id' => $cancelledStatus->id,
        ]);

        return redirect()
            ->route('invoices')
            ->with('success', 'Invoice cancelled successfully.');
    }

    /**
     * Draft invoice.
     */
    public function draft(Request $request)
    {

        $invoicePrefix = Hospital::pluck('invoice_number_prefix')->first();
        $invoice_number = $this->generateInvoiceNumber();
        $invoiceSuffix = now()->format('Y');
        $statusId = InvoiceStatus::where('name', 'Draft')->value('id');

        $items = $request->input('items')?? [];
        $quantity = (empty($items))? 0: count($items);
        $unit_price = ($request->unit_price)? $request->unit_price: 0;
        $net_amount = $unit_price*$quantity;
        $vat = $net_amount*5/100;
        $discount = $request->discount ?? 0;
        $total = $net_amount + $vat - $discount;

        $invoice = Invoice::create([
                    'invoice_date' => $request->invoice_date,
                    'invoice_number' => $invoice_number,
                    'invoice_number_prefix' => $invoicePrefix,
                    'invoice_number_suffix' => $invoiceSuffix,
                    'due_date' => $request->due_date ?: null,
                    'payment_status' => 'Unpaid',
                    'client_id' => $request->client_id ?: null,
                    'service_id' => $request->service_id ?: null,
                    'unit_price' => $request->unit_price?: null,
                    'quantity' => $quantity,
                    'net_amount' =>$net_amount,
                    'vat' => $vat,
                    'discount' => $discount,
                    'total' => $total,
                    'status_id' => $statusId,
                ]);          
        foreach ($request->input('items', []) as $item) {
            $invoice->items()->create([
                    'worker_name' =>
                        $item['worker_name'],
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invoice saved as draft.',
            'redirect' => route('invoices'),
        ]);
    }

    /**
     * Generate invoice number.
     */
    private function generateInvoiceNumber(): string {

        $lastInvoice = DB::table('invoice')
            ->join('service', 'service.id', '=', 'invoice.service_id')
            ->where('service.auto_invoice_number', 1)
            ->orderByDesc('invoice.id')
            ->value('invoice.invoice_number');

        $nextNumber = $lastInvoice? $lastInvoice + 1: 1;

        return str_pad($nextNumber, 4,'0', STR_PAD_LEFT);
    }
}
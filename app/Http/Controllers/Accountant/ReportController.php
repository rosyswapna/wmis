<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;

use App\Models\InvoiceItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display workers report.
     */
    public function workers(Request $request)
    {

        $reportColumns = [
            'worker_name'=>'Worker Name',
            'invoice_number'=>'Invoice Number',
            'invoice_date'=>'Invoice Date',
            'client'=>'Client',
            'service'=>'Service',
            'amount'=>'Amount',
        ];
        $reportData = InvoiceItem::query()
            ->join('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->join('invoice_status', 'invoice.status_id', '=', 'invoice_status.id')
            ->join('client', 'client.id', '=', 'invoice.client_id')
            ->join('service', 'service.id', '=', 'invoice.service_id')
            ->select([
                'invoice_item.worker_name',
                DB::raw("
                    CONCAT_WS(
                        '-',
                        NULLIF(invoice.invoice_number_prefix, ''),
                        invoice.invoice_number,
                        NULLIF(invoice.invoice_number_suffix, '')
                    ) as invoice_number
                "),
                'invoice.invoice_date',
                'client.name as client',
                'service.name as service',
                'invoice.unit_price as amount',
            ])
            ->whereNotIn('invoice_status.name', ['Draft', 'Cancelled'])
            // Worker Name
            ->when($request->filled('worker_name'), function ($query) use ($request) {
                $query->where(
                    'invoice_item.worker_name',
                    'like',
                    '%' . $request->worker_name . '%'
                );
            })

            // From Date
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate(
                    'invoice.invoice_date',
                    '>=',
                    $request->date_from
                );
            })

            // To Date
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate(
                    'invoice.invoice_date',
                    '<=',
                    $request->date_to
                );
            })
            ->orderBy('invoice.invoice_date', 'desc')
            ->paginate(10);

        return view('report.workers', compact('reportColumns','reportData'));
    }
}

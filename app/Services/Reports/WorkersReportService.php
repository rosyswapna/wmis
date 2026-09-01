<?php

namespace App\Services\Reports;

use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Builder;

class WorkersReportService
{
    public function query(array $filters): Builder
    {
        return InvoiceItem::query()
            ->join('invoice', 'invoice.id', '=', 'invoice_item.invoice_id')
            ->join('client', 'client.id', '=', 'invoice.client_id')
            ->join('service', 'service.id', '=', 'invoice.service_id')
            ->join('invoice_status', 'invoice_status.id', '=', 'invoice.status_id')

            ->select([
                'invoice_item.worker_name',
                'invoice.invoice_number',
                'invoice.invoice_date',
                'client.name as client',
                'service.name as service',
                'invoice.unit_price as amount',
            ])
            ->whereNotIn('invoice_status.name',['Draft','Cancelled'])

            ->when(
                !empty($filters['worker_name']),
                fn ($query) =>
                    $query->where(
                        'invoice_item.worker_name',
                        'like',
                        '%' . $filters['worker_name'] . '%'
                    )
            )

            ->when(
                !empty($filters['date_from']),
                fn ($query) =>
                    $query->whereDate(
                        'invoice.invoice_date',
                        '>=',
                        $filters['date_from']
                    )
            )

            ->when(
                !empty($filters['date_to']),
                fn ($query) =>
                    $query->whereDate(
                        'invoice.invoice_date',
                        '<=',
                        $filters['date_to']
                    )
            )

            ->orderBy('invoice.invoice_date', 'desc');
    }

    public function columns()
    {
        return [
            'worker_name'=>'Worker Name',
            'invoice_number'=>'Invoice Number',
            'invoice_date'=>'Invoice Date',
            'client'=>'Client',
            'service'=>'Service',
            'amount'=>'Amount',
        ];
    }
}
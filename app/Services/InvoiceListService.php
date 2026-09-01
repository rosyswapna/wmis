<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InvoiceListService
{
    public function query(array $filters): Builder
    {
        return Invoice::with([ 'client', 'service', 'status', ])  
            ->select([ 
                'id', 
                DB::raw("
                    CONCAT(
                        COALESCE(invoice_number_prefix, ''),
                        CASE
                            WHEN invoice_number_prefix IS NOT NULL
                                AND invoice_number_prefix != ''
                            THEN '-'
                            ELSE ''
                        END,
                        invoice_number,
                        CASE
                            WHEN invoice_number_suffix IS NOT NULL
                                AND invoice_number_suffix != ''
                            THEN '-'
                            ELSE ''
                        END,
                        COALESCE(invoice_number_suffix, '')
                    ) AS invoice_number
                "),
                'invoice_date', 
                'client_id', 
                'service_id', 
                'quantity', 
                'vat', 
                'total', 
                'status_id', ])          
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
            ->when(
                !empty($filters['client_id']),
                fn ($query) =>
                    $query->where(
                        'client_id',
                        '=',
                        $filters['client_id']
                    )
            )
            ->when(
                !empty($filters['status_id']),
                fn ($query) =>
                    $query->where(
                        'status_id',
                        '=',
                        $filters['status_id']
                    )
            )
           ->orderBy('invoice_date', 'desc');
    }
    
}
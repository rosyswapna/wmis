<?php

namespace App\Services\Reports;

use App\Models\Invoice;
use App\Models\PaymentInvoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InvoicePaymentReportService
{
    public function query(array $filters): Builder
    {
        return PaymentInvoice::query()
            ->join('payment', 'payment.id', '=', 'payment_invoice.payment_id')
            ->join('invoice', 'invoice.id', '=', 'payment_invoice.invoice_id')
            ->join('client', 'client.id', '=', 'invoice.client_id')
            ->select([
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
                'invoice.invoice_date',
                'invoice.total as invoice_amount',
                'payment.reference_number',
                'payment.payment_date',
                'payment_invoice.amount as paid_amount',
                'client.name as client_name',
            ])
             // Invoice number filter
            ->when(
                !empty($filters['invoice_number']),
                fn ($query) => $query->whereRaw(
                    "CONCAT(
                        COALESCE(invoice.invoice_number_prefix, ''),
                        CASE
                            WHEN invoice.invoice_number_prefix IS NOT NULL
                                AND invoice.invoice_number_prefix != ''
                            THEN '-'
                            ELSE ''
                        END,
                        invoice.invoice_number,
                        CASE
                            WHEN invoice.invoice_number_suffix IS NOT NULL
                                AND invoice.invoice_number_suffix != ''
                            THEN '-'
                            ELSE ''
                        END,
                        COALESCE(invoice.invoice_number_suffix, '')
                    ) LIKE ?",
                    ['%' . trim($filters['invoice_number']) . '%']
                )
            )

            ->when(
                !empty($filters['date_from']),
                fn ($query) =>
                    $query->whereDate(
                        'payment.payment_date',
                        '>=',
                        $filters['date_from']
                    )
            )

            ->when(
                !empty($filters['date_to']),
                fn ($query) =>
                    $query->whereDate(
                        'payment.payment_date',
                        '<=',
                        $filters['date_to']
                    )
            );
    }

    public function columns()
    {
        return [
            'invoice_number'=>'Invoice Number',
            'invoice_date' => 'Invoice Date',
            'reference_number'=>'Receipt Number',
            'invoice_amount' => 'invoice_amount',
            'payment_date'=>'Payment Date',
            'client_name'=>'Client',
            'paid_amount'=>'Amount Paid',
        ];
    }
}
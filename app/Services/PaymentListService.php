<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PaymentListService
{
    public function query(array $filters): Builder
    {
        return Payment::with([ 'client', 'createdBy'])  
            ->select([ 
                'id',                 
                'payment_date', 
                'reference_number',
                'client_id',
                'created_by', 
                'total_paid', ])          
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
           ->orderBy('payment_date', 'desc');
    }
    
}
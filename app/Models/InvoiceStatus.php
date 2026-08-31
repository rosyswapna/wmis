<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceStatus extends Model
{
    protected $table = 'invoice_status';
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'status_id');
    }
}
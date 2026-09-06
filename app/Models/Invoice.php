<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $fillable = [
        'invoice_date',
        'invoice_number',
        'invoice_number_prefix',
        'invoice_number_suffix',
        'due_date',
        'payment_status',
        'client_id',
        'service_id',
        'status_id',
        'unit_price',
        'quantity',
        'net_amount',
        'vat',
        'discount',
        'total',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'unit_price' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'vat' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class, 'status_id');
    }

    public function payments()
    {
        return $this->belongsToMany(
            Payment::class,
            'payment_invoice',
            'invoice_id',
            'payment_id'
        )->withPivot('amount');
    }

    public function outstanding(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total - $this->payments()
                ->sum('payment_invoice.amount')
        );
    }

    public function invoice_number_full()
    {
        return implode('-', array_filter([
            $this->invoice_number_prefix,
            $this->invoice_number,
            $this->invoice_number_suffix,
        ], fn ($value) => $value !== null && $value !== ''));
    }
}
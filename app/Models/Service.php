<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';
    protected $fillable = [
        'name',
        'description',
        'standard_price',
        'auto_invoice_number',
    ];

    protected $casts = [
        'standard_price' => 'decimal:2',
        'auto_invoice_number' => 'boolean',
    ];
}

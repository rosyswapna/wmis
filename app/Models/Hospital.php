<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hospital extends Model
{
    protected $table = 'hospital';
    protected $fillable = [
        'hospital_name',
        'trade_license_number',
        'ownership_type',
        'tax_registration_number',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'telephone_number',
        'email',
        'website',
        'logo',
        'company_seal',
        'invoice_number_prefix',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
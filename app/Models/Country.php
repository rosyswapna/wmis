<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'country';
    protected $fillable = [
        'name',
        'code',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function hospitals(): HasMany
    {
        return $this->hasMany(Hospital::class);
    }
}
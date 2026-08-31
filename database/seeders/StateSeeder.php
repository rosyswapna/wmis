<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $uae = Country::where('code', 'UAE')->firstOrFail();

        $uaeStates = [
            'Abu Dhabi',
            'Dubai',
            'Sharjah',
            'Ajman',
            'Umm Al Quwain',
            'Ras Al Khaimah',
            'Fujairah',
        ];

        foreach ($uaeStates as $name) {
            State::updateOrCreate(
                [
                    'country_id' => $uae->id,
                    'name' => $name,
                ]
            );
        }
       
    }
}
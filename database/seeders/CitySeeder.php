<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Abu Dhabi' => [
                'Abu Dhabi',
                'Al Ain',
                'Al Dhannah',
                'Madinat Zayed',
            ],

            'Dubai' => [
                'Dubai',
                'Hatta',
            ],

            'Sharjah' => [
                'Sharjah',
                'Khor Fakkan',
                'Kalba',
                'Dibba Al-Hisn',
            ],

            'Ajman' => [
                'Ajman',
                'Masfut',
                'Manama',
            ],

            'Umm Al Quwain' => [
                'Umm Al Quwain',
            ],

            'Ras Al Khaimah' => [
                'Ras Al Khaimah',
            ],

            'Fujairah' => [
                'Fujairah',
                'Dibba Al-Fujairah',
            ],
        ];

        foreach ($cities as $stateName => $cityNames) {
            $state = State::where('name', $stateName)->first();

            if (!$state) {
                continue;
            }

            foreach ($cityNames as $cityName) {
                City::updateOrCreate(
                    [
                        'state_id' => $state->id,
                        'name' => $cityName,
                    ]
                );
            }
        }
    }
}
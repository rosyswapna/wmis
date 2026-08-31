<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::where('code', 'UAE')->firstOrFail();

        $state = State::where('country_id', $country->id)
            ->where('name', 'Abu Dhabi')
            ->firstOrFail();

        $city = City::where('state_id', $state->id)
            ->where('name', 'Abu Dhabi')
            ->firstOrFail();

        Hospital::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'hospital_name' => 'Sample Medical Center',

                'trade_license_number' => 'CN-1234567',

                'ownership_type' => 'Private',

                'tax_registration_number' => '100123456789003',

                'address' => 'Abu Dhabi, United Arab Emirates',

                'country_id' => $country->id,

                'state_id' => $state->id,

                'city_id' => $city->id,

                'telephone_number' => '+971 2 123 4567',

                'email' => 'info@example.com',

                'website' => 'https://example.com',

                'logo' => null,

                'company_seal' => null,

                'invoice_number_prefix' => 'INV',
            ]
        );
    }
}
<?php

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;

class InvoiceStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Draft',
            'New Invoice',
            'Processed',
            'Cancelled',
        ];

        foreach ($statuses as $status) {
            InvoiceStatus::updateOrCreate(
                ['name' => $status],
                ['is_active' => true]
            );
        }
    }
}
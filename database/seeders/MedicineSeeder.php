<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        Medicine::create([
            'name' => 'Paracetamol',
            'batch_number' => 'B123',
            'manufacturer' => 'Pharma Inc.',
            'expiry_date' => '2025-12-31',
            'stock_quantity' => 100,
            'dosage' => '500mg',
        ]);

        Medicine::create([
            'name' => 'Ibuprofen',
            'batch_number' => 'B456',
            'manufacturer' => 'HealthCorp',
            'expiry_date' => '2026-01-15',
            'stock_quantity' => 200,
            'dosage' => '400mg',
        ]);
    }
}


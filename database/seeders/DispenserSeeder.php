<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispenser;

class DispenserSeeder extends Seeder
{
    public function run(): void
    {
        Dispenser::create([
            'name' => 'Dispenser 1',
            'location' => 'Hospital A',
            'model' => 'Model X',
            'capacity' => 45,
            'rows' => 3,
            'columns' => 3,
        ]);

        Dispenser::create([
            'name' => 'Dispenser 2',
            'location' => 'Clinic B',
            'model' => 'Model Y',
            'capacity' => 30,
            'rows' => 3,
            'columns' => 2,
        ]);
    }
}

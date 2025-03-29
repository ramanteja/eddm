<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MedicineSeeder::class,          // Seeder for medicines
            DispenserSeeder::class,         // Seeder for dispensers
            DispenserMedicineSeeder::class, // Seeder for medicines in dispensers
        ]);
    }
}


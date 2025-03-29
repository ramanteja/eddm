<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispenser;
use App\Models\Medicine;

class DispenserMedicineSeeder extends Seeder
{
    public function run(): void
    {
        $dispenser = Dispenser::first(); // Fetch the first dispenser
        $medicine = Medicine::first(); // Fetch the first medicine

        // Ensure both dispenser and medicine exist
        if (!$dispenser || !$medicine) {
            $this->command->info('No dispenser or medicine found. Please seed dispensers and medicines first.');
            return;
        }

        foreach (range(1, $dispenser->rows) as $row) {
            foreach (range(1, $dispenser->columns) as $column) {
                $slot_id = "{$row}-{$column}";
                $dispenser->medicines()->attach($medicine->id, [
                    'slot_id' => $slot_id, // Slot identifier (e.g., 1-1, 1-2)
                    'stock' => 5, // Stock in this slot
                    'capacity' => 5, // Maximum capacity of this slot
                ]);
            }
        }
    }
}

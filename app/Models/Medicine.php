<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'batch_number', 'manufacturer', 'expiry_date', 'stock_quantity', 'dosage'];

    // Relationship with dispensers
    public function dispensers()
    {
        return $this->belongsToMany(Dispenser::class, 'dispenser_medicine')
                    ->withPivot('slot_id', 'stock', 'capacity')
                    ->withTimestamps();
    }
}


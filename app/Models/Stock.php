<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dispenser_id',
        'medicine_id',
        'stock',
        'spiral', // ← ✅ This was missing
    ];

    /**
     * Define the relationship with the Dispenser model.
     */
    public function dispenser()
    {
        return $this->belongsTo(Dispenser::class);
    }

    /**
     * Define the relationship with the Medicine model.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
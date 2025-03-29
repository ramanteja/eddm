<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dispenser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'model',
        'capacity',
        'rows',
        'columns',
        'token',
    ];

    protected static function booted()
    {
        static::creating(function ($dispenser) {
            $dispenser->token = Str::random(60);
        });
    }

    // Relationship with medicines through slots
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'dispenser_medicine')
            ->withPivot('slot_id', 'stock', 'capacity')
            ->withTimestamps();
    }
}

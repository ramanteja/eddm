<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'used',
    ];

    /**
     * Define the relationship with the Order model.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

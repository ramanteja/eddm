<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
	protected $fillable = [
    'user_id',
    'prescription_id',
    'medicine_id',
    'quantity',
    'status',
    'payment_status',
	'spiral',
	];
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function medicine()
	{
		return $this->belongsTo(Medicine::class);
	}
	
	public function dispenser()
	{
		return $this->belongsTo(Dispenser::class); // ✅ Add this if missing
	}
	
	protected static function booted()
	{
		static::updating(function ($order) {
			// Only act if status is changing to "dispensed" and wasn't already dispensed
			if (
				$order->isDirty('status') &&
				$order->status === 'dispensed' &&
				$order->getOriginal('status') !== 'dispensed'
			) {
				$stock = \App\Models\Stock::where('medicine_id', $order->medicine_id)
					->where('dispenser_id', $order->dispenser_id)
					->where('spiral', $order->spiral)
					->first();

				if ($stock) {
					$stock->decrement('stock', $order->quantity);

					// Optional: log if stock drops below critical
					if ($stock->stock <= 5) {
						\Filament\Notifications\Notification::make()
							->title('Low stock alert!')
							->body("Spiral {$order->spiral} in Dispenser {$order->dispenser_id} is low on medicine.")
							->warning()
							->send();
					}
				}
			}
		});
	}

}

<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Stock;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto assign user ID if role is 'User'
        if (auth()->user()->hasRole('User')) {
            $data['user_id'] = auth()->id();
        }

        // Find matching stock for selected dispenser and medicine
        $stock = Stock::where('dispenser_id', $data['dispenser_id'])
            ->where('medicine_id', $data['medicine_id'])
            ->first();

        if (!$stock) {
            Notification::make()
                ->title('Stock not found')
                ->body('No stock found for the selected dispenser and medicine.')
                ->danger()
                ->send();

            $this->halt();
        }

        if ($stock->stock < $data['quantity']) {
            Notification::make()
                ->title('Not enough stock available')
                ->body('Please select a lower quantity and try again.')
                ->warning()
                ->send();

            $this->halt();
        }

        // Prevent dispatching if payment is not done
        if (isset($data['status']) && $data['status'] === 'dispensed' && !$data['payment_status']) {
            Notification::make()
                ->danger()
                ->title('Cannot mark as Dispensed')
                ->body('Payment must be completed before dispatching medicine.')
                ->send();

            $this->halt();
        }

        $data['spiral'] = $stock->spiral;

        return $data;
    }
}

<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Stock;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->hasAnyRole(['Admin', 'SuperAdmin'])),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure users can only edit their own orders
        if (auth()->user()->hasRole('User') && $data['user_id'] !== auth()->id()) {
            abort(403, 'Unauthorized access to edit this order.');
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Prevent users from changing user_id
        if (auth()->user()->hasRole('User')) {
            $data['user_id'] = auth()->id();
        }

        $record = $this->record;

        // Block edit if payment is already completed
        if ((int) $record->payment_status === 1) {
            Notification::make()
                ->title('Payment already completed')
                ->body('This order cannot be edited after payment is completed.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Prevent dispatching if payment not completed
        if (isset($data['status']) && $data['status'] === 'dispensed' && !$data['payment_status']) {
            Notification::make()
                ->danger()
                ->title('Cannot mark as Dispensed')
                ->body('Payment must be completed before dispatching medicine.')
                ->send();

            $this->halt();
        }

        // Stock validation
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

        $data['spiral'] = $stock->spiral;

        return $data;
    }
}
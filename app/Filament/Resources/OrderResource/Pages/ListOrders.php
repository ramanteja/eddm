<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(function () {
                    $user = auth()->user();

                    // Allow Create only for SuperAdmin, Admin, or User roles
                    return $user->hasAnyRole(['SuperAdmin', 'Admin', 'User']);
                }),
        ];
    }
}

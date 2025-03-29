<?php

namespace App\Filament\Resources\DispenserResource\Pages;

use App\Filament\Resources\DispenserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispensers extends ListRecords
{
    protected static string $resource = DispenserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

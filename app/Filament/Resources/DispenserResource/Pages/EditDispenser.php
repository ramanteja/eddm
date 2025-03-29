<?php

namespace App\Filament\Resources\DispenserResource\Pages;

use App\Filament\Resources\DispenserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispenser extends EditRecord
{
    protected static string $resource = DispenserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

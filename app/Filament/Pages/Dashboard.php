<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }
}
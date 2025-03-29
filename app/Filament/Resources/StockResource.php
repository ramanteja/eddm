<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Filament\Resources\StockResource\RelationManagers;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockResource extends Resource
{
    public static function canViewAny(): bool
	{
		return auth()->user()->hasAnyRole(['Admin', 'SuperAdmin']);
	}
	
	protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dispenser_id')
                    ->label('Dispenser')
                    ->relationship('dispenser', 'name')
                    ->required(),

                Forms\Components\Select::make('medicine_id')
                    ->label('Medicine')
                    ->relationship('medicine', 'name')
                    ->required(),

                Forms\Components\TextInput::make('stock')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                Forms\Components\TextInput::make('spiral')
                    ->numeric()
                    ->minValue(1)
                    ->label('Spiral Number')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dispenser.name')->label('Dispenser'),
                Tables\Columns\TextColumn::make('medicine.name')->label('Medicine'),
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\TextColumn::make('spiral'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Updated')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}


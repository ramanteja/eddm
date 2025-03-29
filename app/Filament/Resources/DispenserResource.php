<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispenserResource\Pages;
use App\Models\Dispenser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DispenserResource extends Resource
{
    public static function canViewAny(): bool
	{
		return auth()->user()->hasAnyRole(['Admin', 'SuperAdmin']);
	}
	
	protected static ?string $model = Dispenser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('rows')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('columns')
                    ->required()
                    ->numeric(),

                // Only show token on edit, not on create
                Forms\Components\TextInput::make('token')
                    ->label('Auth Token')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('location')->searchable(),
                Tables\Columns\TextColumn::make('model')->searchable(),
                Tables\Columns\TextColumn::make('capacity')->sortable(),
                Tables\Columns\TextColumn::make('rows')->sortable(),
                Tables\Columns\TextColumn::make('columns')->sortable(),

                // Show token in the table for copy-paste ease
                Tables\Columns\TextColumn::make('token')
                    ->label('Auth Token')
                    ->copyable()
                    ->copyMessage('Token copied!')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispensers::route('/'),
            'create' => Pages\CreateDispenser::route('/create'),
            'edit' => Pages\EditDispenser::route('/{record}/edit'),
        ];
    }
}

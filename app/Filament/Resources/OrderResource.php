<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Dispenser;
use App\Models\Medicine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
	
	protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyRole(['SuperAdmin', 'Admin', 'User']);
    }

    public static function form(Form $form): Form
    {
        $isUser = auth()->user()->hasRole('User');

        return $form
            ->schema([
                Forms\Components\Select::make('dispenser_id')
                    ->label('Dispenser')
                    ->relationship('dispenser', 'name')
                    ->live()
                    ->required()
                    ->disabled(fn($record) => $isUser && $record && $record->payment_status),

                Forms\Components\Select::make('medicine_id')
                    ->label('Medicine (based on Dispenser)')
                    ->options(function (callable $get) {
                        $dispenserId = $get('dispenser_id');

                        if (!$dispenserId) return [];

                        return \App\Models\Stock::where('dispenser_id', $dispenserId)
                            ->where('stock', '>', 0)
                            ->with('medicine')
                            ->get()
                            ->pluck('medicine.name', 'medicine_id')
                            ->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->hint('Only available medicines in stock will appear')
                    ->afterStateUpdated(fn() => null)
                    ->disabled(fn($record) => $isUser && $record && $record->payment_status),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->disabled(fn($record) => $isUser && $record && $record->payment_status),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'requested' => 'Requested',
                        'dispensed' => 'Dispensed',
                    ])
                    ->default('requested')
                    ->required()
                    ->disabled(fn($record) => $isUser && $record && $record->payment_status),

                Forms\Components\Toggle::make('payment_status')
                    ->label('Payment Status')
                    ->required()
					->disabled(fn ($get) => $isUser || $get('status') === 'dispensed'), // Always disabled for 'User' and when status is dispensed
                    //->disabled($isUser), // Always disabled for 'User'

                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->disabled($isUser)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('dispenser.name')->label('Dispenser'),
                Tables\Columns\TextColumn::make('medicine.name')->label('Medicine'),
                Tables\Columns\TextColumn::make('spiral')->label('Spiral Slot'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\IconColumn::make('payment_status')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('User')) {
            return $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
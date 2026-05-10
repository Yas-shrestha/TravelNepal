<?php

namespace App\Filament\Resources\Trips\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BikeRentalsRelationManager extends RelationManager
{
    protected static string $relationship = 'bikeRentals';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bike Rental')
                    ->columns(2)
                    ->schema([
                        TextInput::make('model')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('engine_cc')
                            ->required()
                            ->numeric()
                            ->suffix('cc'),

                        TextInput::make('price_per_day_usd')
                            ->required()
                            ->numeric()
                            ->prefix('$'),

                        Toggle::make('is_available')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('engine_cc')
                    ->numeric(),
                TextColumn::make('price_per_day_usd')
                    ->money('USD'),
                IconColumn::make('is_available')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}

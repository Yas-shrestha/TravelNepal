<?php

namespace App\Filament\Resources\Trips\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItineraryDaysRelationManager extends RelationManager
{
    protected static string $relationship = 'itineraryDays';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Itinerary Day')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('day_number')
                            ->required()
                            ->numeric(),

                        TextInput::make('title')
                            ->required(),

                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('accommodation')
                            ->nullable(),

                        TextInput::make('meals_included')
                            ->nullable(),

                        TextInput::make('distance_km')
                            ->numeric()
                            ->nullable()
                            ->suffix('km'),

                        TextInput::make('elevation_gain_m')
                            ->integer()
                            ->nullable()
                            ->suffix('m'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('accommodation'),
                TextColumn::make('meals_included'),
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
            ->defaultSort('day_number', 'asc');
    }
}

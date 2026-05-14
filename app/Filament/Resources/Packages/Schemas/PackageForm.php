<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('trip_id')
                            ->relationship('trip', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('tier')
                            ->options([
                                'standard' => 'Standard',
                                'premium' => 'Premium',
                                'luxury' => 'Luxury',
                            ])
                            ->required(),

                        TextInput::make('title')
                            ->required(),

                        TextInput::make('price_usd')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->label('Price (USD)'),


                        Textarea::make('description')
                            ->nullable()
                            ->columnSpanFull(),
                        Toggle::make('is_popular')
                            ->label('Mark as Most Popular')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                    ]),
            ]);
    }
}

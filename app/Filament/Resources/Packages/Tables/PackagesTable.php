<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trip.title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tier')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'standard' => 'gray',
                        'premium' => 'warning',
                        'luxury' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('price_usd')
                    ->prefix('$')
                    ->sortable(),

                IconColumn::make('is_popular')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('trip')
                    ->relationship('trip', 'title'),

                SelectFilter::make('tier')
                    ->options([
                        'standard' => 'Standard',
                        'premium' => 'Premium',
                        'luxury' => 'Luxury',
                    ]),

                TernaryFilter::make('is_popular'),

                TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

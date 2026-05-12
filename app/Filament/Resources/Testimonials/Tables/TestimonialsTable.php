<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable(),

                TextColumn::make('trip.title')
                    ->label('Trip')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('rating')
                    ->badge()
                    ->color(fn(string $state): string => match ((int) $state) {
                        5       => 'success',
                        4       => 'success',
                        3       => 'warning',
                        2       => 'danger',
                        1       => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Approved'),

                SelectFilter::make('trip')
                    ->relationship('trip', 'title'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheck ?? 'heroicon-o-check')
                    ->visible(fn($record): bool => !$record->is_approved)
                    ->action(function ($record): void {
                        $record->update(['is_approved' => true]);
                        Notification::make()
                            ->success()
                            ->title('Testimonial approved')
                            ->send();
                    }),

                Action::make('unapprove')
                    ->label('Unapprove')
                    ->color('danger')
                    ->visible(fn($record): bool => $record->is_approved)
                    ->action(function ($record): void {
                        $record->update(['is_approved' => false]);
                        Notification::make()
                            ->warning()
                            ->title('Testimonial unapproved')
                            ->send();
                    }),

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

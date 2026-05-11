<?php

namespace App\Filament\Resources\Enquiries\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class EnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('trip.title')
                    ->label('Trip of Interest')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'unread'  => 'warning',
                        'read'    => 'gray',
                        'replied' => 'success',
                        default   => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'unread'  => 'Unread',
                        'read'    => 'Read',
                        'replied' => 'Replied',
                    ]),
            ])
            ->recordActions([
                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->color('gray')
                    ->icon(Heroicon::OutlinedEye ?? 'heroicon-o-eye')
                    ->visible(fn($record): bool => $record->status === 'unread')
                    ->action(function ($record): void {
                        $record->update(['status' => 'read']);
                        Notification::make()
                            ->success()
                            ->title('Marked as read')
                            ->send();
                    }),

                Action::make('markAsReplied')
                    ->label('Mark as Replied')
                    ->color('success')
                    ->visible(fn($record): bool => $record->status === 'read')
                    ->action(function ($record): void {
                        $record->update(['status' => 'replied']);
                        Notification::make()
                            ->success()
                            ->title('Marked as replied')
                            ->send();
                    }),

                EditAction::make()
                    ->label('View'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

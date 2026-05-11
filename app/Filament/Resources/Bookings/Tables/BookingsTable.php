<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BookingsTable
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

                TextColumn::make('trip.title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('package.tier')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'standard' => 'gray',
                        'premium' => 'warning',
                        'luxury' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('preferred_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('group_size')
                    ->numeric(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('has_own_bike')
                    ->label('Own Bike')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('trip')
                    ->relationship('trip', 'title'),

                TernaryFilter::make('has_own_bike'),

                TernaryFilter::make('has_license'),
            ])
            ->recordActions([
                Action::make('confirmBooking')
                    ->label('Confirm Booking')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(): bool => self::isSuperAdmin())
                    ->action(function ($record): void {
                        $record->update(['status' => 'confirmed']);

                        $isLicenseMissing = $record->has_license === true
                            && (
                                blank($record->license_number)
                                || blank($record->license_country)
                                || blank($record->license_type)
                                || blank($record->license_image)
                            );

                        if ($isLicenseMissing) {
                            Notification::make()
                                ->warning()
                                ->title('Booking confirmed')
                                ->body('License details appear incomplete. Please verify manually before final processing.')
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title('Booking confirmed')
                            ->send();
                    }),

                Action::make('cancelBooking')
                    ->label('Cancel Booking')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(): bool => self::isSuperAdmin())
                    ->action(function ($record): void {
                        $record->update(['status' => 'cancelled']);

                        Notification::make()
                            ->success()
                            ->title('Booking cancelled')
                            ->send();
                    }),

                EditAction::make()
                    ->label('View'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected static function isSuperAdmin(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('super_admin') || $user->hasRole('Super Admin');
        }

        if (isset($user->is_super_admin)) {
            return (bool) $user->is_super_admin;
        }

        if (isset($user->role)) {
            return in_array((string) $user->role, ['super_admin', 'Super Admin'], true);
        }

        if (method_exists($user, 'can')) {
            return $user->can('super-admin');
        }

        return false;
    }
}

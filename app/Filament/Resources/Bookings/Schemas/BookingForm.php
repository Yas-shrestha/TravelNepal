<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Booking Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('email')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('phone')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('preferred_date')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('group_size')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('message')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Package')
                    ->columns(2)
                    ->schema([
                        TextInput::make('trip.title')
                            ->label('Trip')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('package.tier')
                            ->label('Package Tier')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('package.price_usd')
                            ->label('Package Price')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Motorbike Details')
                    ->columns(2)
                    ->visible(fn($record): bool => $record !== null && $record->has_license !== null)
                    ->schema([
                        TextInput::make('has_license')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('license_number')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('license_country')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('license_type')
                            ->disabled()
                            ->dehydrated(false),

                        FileUpload::make('license_image')
                            ->image()
                            ->disabled()
                            ->dehydrated(false)
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),

                        TextInput::make('has_own_bike')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('own_bike_model')
                            ->disabled()
                            ->dehydrated(false),

                        Select::make('rental_bike_id')
                            ->relationship('rentalBike', 'model')
                            ->label('Rental Bike')
                            ->disabled()
                            ->dehydrated(false)
                            ->searchable()
                            ->preload(),

                        TextInput::make('rental_cost_usd')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->disabled(fn(): bool => ! self::isSuperAdmin()),
                    ])
            ]);
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

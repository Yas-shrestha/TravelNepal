<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enquiry Details')
                    ->columnSpanFull()
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

                        TextInput::make('subject')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('message')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Trip of Interest')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('trip_title')
                            ->label('Trip')
                            ->formatStateUsing(fn($record) => $record?->trip?->title ?? 'No trip specified')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Section::make('Status')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->options([
                                'unread'  => 'Unread',
                                'read'    => 'Read',
                                'replied' => 'Replied',
                            ])
                            ->required(),
                    ]),
            ]);
    }
}

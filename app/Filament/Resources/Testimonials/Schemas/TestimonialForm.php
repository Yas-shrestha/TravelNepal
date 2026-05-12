<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Reviewer Details')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('location')
                            ->required()
                            ->placeholder('e.g. Sydney, Australia'),

                        Select::make('trip_id')
                            ->relationship('trip', 'title')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->label('Trip'),

                        Select::make('rating')
                            ->options([
                                '1' => '1 Star',
                                '2' => '2 Stars',
                                '3' => '3 Stars',
                                '4' => '4 Stars',
                                '5' => '5 Stars',
                            ])
                            ->required(),

                        Textarea::make('quote')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('avatar')
                            ->image()
                            ->nullable()
                            ->directory('testimonials/avatars')
                            ->columnSpanFull(),

                        Toggle::make('is_approved')
                            ->label('Approved — visible on frontend')
                            ->default(false),
                    ]),
            ]);
    }
}

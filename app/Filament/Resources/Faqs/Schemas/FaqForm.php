<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQ Details')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('question')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('answer')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->helperText('Lower number appears first'),
                    ]),
            ]);
    }
}

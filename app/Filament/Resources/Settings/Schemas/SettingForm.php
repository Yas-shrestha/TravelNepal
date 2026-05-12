<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setting Details')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('e.g. site_name, facebook_url, phone')
                            ->disabled(fn(): bool => Auth::user()?->role !== 'super_admin')
                            ->dehydrated(fn(): bool => Auth::user()?->role === 'super_admin'),

                        Select::make('group')
                            ->options([
                                'general' => 'General',
                                'contact' => 'Contact',
                                'social'  => 'Social',
                                'seo'     => 'SEO',
                                'other'   => 'Other',
                            ])
                            ->required()
                            ->default('general')
                            ->disabled(fn(): bool => Auth::user()?->role !== 'super_admin')
                            ->dehydrated(fn(): bool => Auth::user()?->role === 'super_admin'),

                        Textarea::make('value')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

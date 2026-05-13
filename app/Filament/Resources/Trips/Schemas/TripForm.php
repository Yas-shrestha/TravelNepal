<?php

namespace App\Filament\Resources\Trips\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('slug', Str::slug($state))
                            )
                            ->columnSpan(2),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->columnSpan(2),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('difficulty')
                            ->options([
                                'easy'        => 'Easy',
                                'moderate'    => 'Moderate',
                                'challenging' => 'Challenging',
                                'extreme'     => 'Extreme',
                            ])
                            ->required(),
                    ]),

                Section::make('Trip Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('duration_days')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('days'),

                        TextInput::make('best_season')
                            ->required()
                            ->placeholder('e.g. March–May, Sept–Nov'),

                        TextInput::make('min_group_size')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Min Group Size'),

                        TextInput::make('max_group_size')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Max Group Size'),

                        TextInput::make('max_altitude_m')
                            ->numeric()
                            ->nullable()
                            ->suffix('m')
                            ->label('Max Altitude (m)')
                            ->helperText('Trekking & hikes only — leave blank for motorbike/cycling'),

                        TextInput::make('route_distance_km')
                            ->numeric()
                            ->nullable()
                            ->suffix('km')
                            ->label('Route Distance (km)')
                            ->helperText('Motorbike & cycling only — leave blank for trekking/hikes'),
                    ]),

                Section::make('Description')
                    ->schema([
                        Textarea::make('overview')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        TagsInput::make('highlights')
                            ->required()
                            ->placeholder('Add a highlight and press Enter')
                            ->columnSpanFull(),
                    ]),

                Section::make('Cover Image')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->image()
                            ->disk('public')
                            ->directory('trips/covers')
                            ->automaticallyCropImagesToAspectRatio('16:9')
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1600')
                            ->automaticallyResizeImagesToHeight('900')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Settings')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Featured on Homepage')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active (visible to public)')
                            ->default(true),

                        Toggle::make('requires_bike')
                            ->label('Requires Motorcycle')
                            ->default(false)
                            ->live()
                            ->helperText('Enable for motorbike tour trips'),

                        Toggle::make('bike_rental_available')
                            ->label('Bike Rental Available')
                            ->default(false)
                            ->visible(fn($get) => $get('requires_bike'))
                            ->helperText('Show rental options in booking form'),
                    ]),
            ]);
    }
}

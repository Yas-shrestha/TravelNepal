<?php

namespace App\Filament\Resources\Trips\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TripImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gallery Image')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image_path')
                            ->required()
                            ->image()
                            ->directory('trips/gallery')
                            ->columnSpanFull(),

                        TextInput::make('caption')
                            ->nullable()
                            ->columnSpanFull(),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image_path')
                    ->label('Image'),
                TextColumn::make('caption'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}

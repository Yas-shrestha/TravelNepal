<?php

namespace App\Filament\Resources\Trips\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TripFaqsRelationManager extends RelationManager
{
    protected static string $relationship = 'faqs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Trip FAQ')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('question')
                            ->required(),

                        Textarea::make('answer')
                            ->required()
                            ->rows(4),

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
                TextColumn::make('question')
                    ->searchable(),
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

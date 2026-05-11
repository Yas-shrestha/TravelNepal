<?php

namespace App\Filament\Resources\Packages\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackageInclusionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inclusions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Inclusion')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('type')
                            ->options([
                                'inclusion' => 'Inclusion',
                                'exclusion' => 'Exclusion',
                            ])
                            ->required(),

                        TextInput::make('description')
                            ->required(),

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
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'inclusion' => 'success',
                        'exclusion' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('description')
                    ->searchable(),

                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
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

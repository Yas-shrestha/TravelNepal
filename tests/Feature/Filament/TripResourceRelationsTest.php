<?php

use App\Filament\Resources\Trips\RelationManagers\BikeRentalsRelationManager;
use App\Filament\Resources\Trips\RelationManagers\ItineraryDaysRelationManager;
use App\Filament\Resources\Trips\RelationManagers\TripAttractionsRelationManager;
use App\Filament\Resources\Trips\RelationManagers\TripFaqsRelationManager;
use App\Filament\Resources\Trips\RelationManagers\TripImagesRelationManager;
use App\Filament\Resources\Trips\TripResource;

it('registers trip relation managers', function () {
    expect(TripResource::getRelations())
        ->toBe([
            ItineraryDaysRelationManager::class,
            TripAttractionsRelationManager::class,
            TripImagesRelationManager::class,
            TripFaqsRelationManager::class,
            BikeRentalsRelationManager::class,
        ]);
});

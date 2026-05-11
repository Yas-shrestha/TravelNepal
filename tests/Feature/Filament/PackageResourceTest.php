<?php

use App\Filament\Resources\Packages\PackageResource;
use App\Filament\Resources\Packages\RelationManagers\PackageInclusionsRelationManager;

it('registers package inclusions relation manager', function () {
    expect(PackageResource::getRelations())
        ->toBe([
            PackageInclusionsRelationManager::class,
        ]);
});

it('has index create and edit pages only', function () {
    expect(PackageResource::getPages())
        ->toHaveKeys(['index', 'create', 'edit'])
        ->not->toHaveKey('view');
});

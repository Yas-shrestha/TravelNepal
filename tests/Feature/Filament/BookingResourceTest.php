<?php

use App\Filament\Resources\Bookings\BookingResource;

it('has only index and edit pages', function () {
    expect(BookingResource::getPages())
        ->toHaveKeys(['index', 'edit'])
        ->not->toHaveKey('create')
        ->not->toHaveKey('view');
});

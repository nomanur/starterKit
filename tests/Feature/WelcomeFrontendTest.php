<?php

declare(strict_types=1);

use Devrabiul\LaravelGeoGenius\LaravelGeoGenius;
use Devrabiul\LaravelGeoGenius\Services\GeoLocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the welcome page in English for default users', function (): void {
    $geoMock = Mockery::mock(GeoLocationService::class);
    $geoMock->shouldReceive('getCountryCode')->andReturn('US');

    $geniusMock = Mockery::mock(LaravelGeoGenius::class);
    $geniusMock->shouldReceive('geo')->andReturn($geoMock);

    app()->instance(LaravelGeoGenius::class, $geniusMock);

    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee("Let's get started");
    $response->assertDontSee('চলুন শুরু করা যাক');
    expect(app()->getLocale())->toBe('en');
});

it('renders the welcome page in Bengali for users from Bangladesh', function (): void {
    $geoMock = Mockery::mock(GeoLocationService::class);
    $geoMock->shouldReceive('getCountryCode')->andReturn('BD');

    $geniusMock = Mockery::mock(LaravelGeoGenius::class);
    $geniusMock->shouldReceive('geo')->andReturn($geoMock);

    app()->instance(LaravelGeoGenius::class, $geniusMock);

    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('চলুন শুরু করা যাক');
    $response->assertDontSee("Let's get started");
    expect(app()->getLocale())->toBe('bn');
});

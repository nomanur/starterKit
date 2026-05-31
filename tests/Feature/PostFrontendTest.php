<?php

declare(strict_types=1);

use App\Models\Post;
use Devrabiul\LaravelGeoGenius\LaravelGeoGenius;
use Devrabiul\LaravelGeoGenius\Services\GeoLocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays English post titles by default when user is not from Bangladesh', function (): void {
    $geoMock = Mockery::mock(GeoLocationService::class);
    $geoMock->shouldReceive('getCountryCode')->andReturn('US');

    $geniusMock = Mockery::mock(LaravelGeoGenius::class);
    $geniusMock->shouldReceive('geo')->andReturn($geoMock);

    app()->instance(LaravelGeoGenius::class, $geniusMock);

    Post::factory()->create([
        'title' => [
            'en' => 'English Post Title',
            'bn' => 'বাংলা পোস্টের শিরোনাম',
        ],
    ]);

    $response = $this->get(route('post.index'));

    $response->assertSuccessful();
    $response->assertSee('English Post Title');
    $response->assertDontSee('বাংলা পোস্টের শিরোনাম');
    expect(app()->getLocale())->toBe('en');
});

it('displays Bangla post titles when user is from Bangladesh', function (): void {
    $geoMock = Mockery::mock(GeoLocationService::class);
    $geoMock->shouldReceive('getCountryCode')->andReturn('BD');

    $geniusMock = Mockery::mock(LaravelGeoGenius::class);
    $geniusMock->shouldReceive('geo')->andReturn($geoMock);

    app()->instance(LaravelGeoGenius::class, $geniusMock);

    Post::factory()->create([
        'title' => [
            'en' => 'English Post Title',
            'bn' => 'বাংলা পোস্টের শিরোনাম',
        ],
    ]);

    $response = $this->get(route('post.index'));

    $response->assertSuccessful();
    $response->assertSee('বাংলা পোস্টের শিরোনাম');
    $response->assertDontSee('English Post Title');
    expect(app()->getLocale())->toBe('bn');
});

it('falls back to English when Bangla translation is missing', function (): void {
    $geoMock = Mockery::mock(GeoLocationService::class);
    $geoMock->shouldReceive('getCountryCode')->andReturn('BD');

    $geniusMock = Mockery::mock(LaravelGeoGenius::class);
    $geniusMock->shouldReceive('geo')->andReturn($geoMock);

    app()->instance(LaravelGeoGenius::class, $geniusMock);

    Post::factory()->create([
        'title' => [
            'en' => 'English Post Only',
        ],
    ]);

    $response = $this->get(route('post.index'));

    $response->assertSuccessful();
    $response->assertSee('English Post Only');
    expect(app()->getLocale())->toBe('bn');
});

it('respects the manual session override if set', function (): void {
    $post = Post::factory()->create([
        'title' => [
            'en' => 'English Post Title',
            'bn' => 'বাংলা পোস্টের শিরোনাম',
        ],
    ]);

    $response = $this->withSession(['locale' => 'bn'])->get(route('post.index'));

    $response->assertSuccessful();
    $response->assertSee('বাংলা পোস্টের শিরোনাম');
    $response->assertDontSee('English Post Title');
    expect(app()->getLocale())->toBe('bn');
});

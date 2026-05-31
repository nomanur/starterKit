<?php

declare(strict_types=1);

use App\Filament\Resources\LanguageLines\LanguageLineResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Spatie\TranslationLoader\LanguageLine;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($user);
});

test('super admin can access list language lines page', function (): void {
    LanguageLine::create([
        'group' => 'welcome',
        'key' => 'custom_welcome_message',
        'text' => [
            'en' => 'Welcome to Database translation',
            'bn' => 'ডেটাবেস অনুবাদে স্বাগতম',
        ],
    ]);

    $this->get(LanguageLineResource::getUrl('index'))
        ->assertSuccessful()
        ->assertSee('custom_welcome_message');
});

test('super admin can load create language line page', function (): void {
    $this->get(LanguageLineResource::getUrl('create'))
        ->assertSuccessful()
        ->assertSee('Group')
        ->assertSee('Key');
});

test('spatie translation loader falls back to database correctly', function (): void {
    LanguageLine::create([
        'group' => 'welcome',
        'key' => 'db_translated_key',
        'text' => [
            'en' => 'Database English Value',
            'bn' => 'ডেটাবেস বাংলা মান',
        ],
    ]);

    app()->setLocale('en');
    expect(trans('welcome.db_translated_key'))->toBe('Database English Value');

    app()->setLocale('bn');
    expect(trans('welcome.db_translated_key'))->toBe('ডেটাবেস বাংলা মান');
});

<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('guests are forbidden or redirected from accessing log-viewer', function (): void {
    $response = $this->get('/log-viewer');

    // log-viewer package redirects guests to login or returns 403/forbidden depending on config.
    // Let's assert it is not successful (not 200).
    expect($response->status())->not->toBe(200);
});

test('regular users without super_admin role are forbidden from accessing log-viewer', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/log-viewer')
        ->assertForbidden();
});

test('super admin users with super_admin role can successfully access log-viewer', function (): void {
    // Create the super_admin role
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    // Clear permission cache
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($user)
        ->get('/log-viewer')
        ->assertSuccessful();
});

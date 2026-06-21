<?php

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('super admin can access list users page and view roles column', function (): void {
    // Ensure super_admin role exists
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    // Clear Spatie's permission cache
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($user)
        ->get(UserResource::getUrl('index'))
        ->assertSuccessful()
        ->assertSee($user->name);
});

test('unauthenticated users are redirected from list users page', function (): void {
    $this->get(UserResource::getUrl('index'))
        ->assertRedirect('/admin/login');
});

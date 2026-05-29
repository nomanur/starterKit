<?php

use App\Models\User;
use Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('super admin can access queue monitor resource list page', function (): void {
    // Ensure super_admin role exists
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    // Clear Spatie's permission cache
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($user)
        ->get(QueueMonitorResource::getUrl('index'))
        ->assertSuccessful();
});

test('unauthenticated users are redirected from queue monitor list page', function (): void {
    $this->get(QueueMonitorResource::getUrl('index'))
        ->assertRedirect('/admin/login');
});

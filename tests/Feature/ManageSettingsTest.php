<?php

use App\Filament\Pages\ManageSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

test('guests are redirected from manage settings page', function (): void {
    $this->get(ManageSettings::getUrl())
        ->assertRedirect('/admin/login');
});

test('super admin can view manage settings page and edit env values', function (): void {
    // Backup current .env file
    $envPath = base_path('.env');
    $backupPath = base_path('.env.testbackup');
    if (file_exists($envPath)) {
        copy($envPath, $backupPath);
    }

    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    Livewire::actingAs($user)
        ->test(ManageSettings::class)
        ->assertFormSet([
            'APP_NAME' => env('APP_NAME'),
        ])
        ->set('data.APP_NAME', 'StarterKit Testing')
        ->call('save')
        ->assertHasNoErrors();

    // Verify it was updated in the .env file
    $envContent = file_get_contents($envPath);
    expect($envContent)->toContain('APP_NAME="StarterKit Testing"');

    // Restore original .env file
    if (file_exists($backupPath)) {
        copy($backupPath, $envPath);
        unlink($backupPath);
    }
});

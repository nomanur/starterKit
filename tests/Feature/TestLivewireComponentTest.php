<?php

use App\Livewire\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('component renders successfully and displays the user details', function (): void {
    $user = User::factory()->create([
        'name' => 'Demo User Test',
    ]);

    Livewire::test(Test::class)
        ->assertSee('Demo User Test')
        ->assertSee('Upload Image')
        ->assertSee('No avatar uploaded');
});

test('can upload a photo and save it to the media library', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.jpg');

    Livewire::test(Test::class)
        ->set('photo', $file)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('photo', null)
        ->assertSet('successMessage', 'Photo uploaded and saved to Spatie Media Library successfully!');

    // Verify it is saved to spatie media library
    expect($user->fresh()->getMedia('avatars'))->toHaveCount(1);
    expect($user->fresh()->getFirstMedia('avatars')->file_name)->toBe('avatar.jpg');
});

test('subsequent uploads automatically replace the previous file due to singleFile collection', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    // 1. Upload first file
    $file1 = UploadedFile::fake()->image('first_avatar.jpg');
    Livewire::test(Test::class)
        ->set('photo', $file1)
        ->call('save');

    expect($user->fresh()->getMedia('avatars'))->toHaveCount(1);
    expect($user->fresh()->getFirstMedia('avatars')->file_name)->toBe('first_avatar.jpg');

    // 2. Upload second file
    $file2 = UploadedFile::fake()->image('second_avatar.jpg');
    Livewire::test(Test::class)
        ->set('photo', $file2)
        ->call('save');

    // The avatars collection should still only have 1 file, and it must be the second file!
    expect($user->fresh()->getMedia('avatars'))->toHaveCount(1);
    expect($user->fresh()->getFirstMedia('avatars')->file_name)->toBe('second_avatar.jpg');
});

test('can delete the avatar from the media library', function (): void {
    Storage::fake('public');

    $user = User::factory()->create();

    // Directly seed media using Spatie's API
    $file = UploadedFile::fake()->image('profile.jpg');
    $user->addMedia($file->getRealPath())
        ->usingFileName('profile.jpg')
        ->toMediaCollection('avatars');

    expect($user->fresh()->getMedia('avatars'))->toHaveCount(1);

    Livewire::test(Test::class)
        ->call('deleteAvatar')
        ->assertHasNoErrors()
        ->assertSet('successMessage', 'Avatar deleted successfully!');

    expect($user->fresh()->getMedia('avatars'))->toHaveCount(0);
});

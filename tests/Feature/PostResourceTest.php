<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    actingAs($user);
});

it('renders the edit page without [object Object]', function (): void {
    $post = Post::factory()->create();

    $response = $this->get(PostResource::getUrl('edit', ['record' => $post->id]));

    $response->assertSuccessful();
    $response->assertDontSee('[object Object]');
});

it('renders form fields inside translatable tabs on create', function (): void {
    $response = $this->get(PostResource::getUrl('create'));

    $response->assertSuccessful();
    $response->assertSee('English');
    $response->assertSee('Spanish');
    $response->assertSee('title');
    $response->assertSee('content');
});

it('renders form fields inside translatable tabs on edit', function (): void {
    $post = Post::factory()->create([
        'title' => ['en' => 'UniqueEnglishTitle', 'es' => 'UniqueSpanishTitle'],
    ]);

    $response = $this->get(PostResource::getUrl('edit', ['record' => $post->id]));

    $response->assertSuccessful();
    $response->assertSee('English');
    $response->assertSee('Spanish');
    $response->assertSee('UniqueEnglishTitle');
    $response->assertSee('UniqueSpanishTitle');
});

it('can search posts by title in the table', function (): void {
    $postA = Post::factory()->create([
        'title' => ['en' => 'MatchThisTitle', 'es' => 'NoMatchES'],
    ]);
    $postB = Post::factory()->create([
        'title' => ['en' => 'OtherTitle', 'es' => 'OtherTitleES'],
    ]);

    Livewire::test(ListPosts::class)
        ->searchTable('MatchThisTitle')
        ->assertCanSeeTableRecords([$postA])
        ->assertCanNotSeeTableRecords([$postB]);
});

it('does not find records when searching for other locale values if they are not in active locale', function (): void {
    $post = Post::factory()->create([
        'title' => ['en' => 'MatchThisTitle', 'es' => 'SecretSpanishWord'],
    ]);

    Livewire::test(ListPosts::class)
        ->searchTable('SecretSpanishWord')
        ->assertCanNotSeeTableRecords([$post]);
});

it('fails validation when title is missing', function (): void {
    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => ['en' => ''],
        ])
        ->call('create')
        ->assertHasErrors(['data.title.en' => 'required']);
});

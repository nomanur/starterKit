<?php

use App\Enums\SocialiteProvider;
use App\Livewire\Socialite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite as SocialiteFacade;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function configureSocialiteProvider(string $provider = 'github'): void
{
    config()->set("services.{$provider}", [
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client-secret',
        'redirect' => "/auth/{$provider}/callback",
    ]);
}

function mockSocialiteUser(string $driver = 'github', array $overrides = []): void
{
    $mockUser = Mockery::mock(SocialiteUser::class);
    $mockUser->shouldReceive('getId')->andReturn(array_key_exists('id', $overrides) ? $overrides['id'] : '12345');
    $mockUser->shouldReceive('getName')->andReturn(array_key_exists('name', $overrides) ? $overrides['name'] : 'Test User');
    $mockUser->shouldReceive('getEmail')->andReturn(array_key_exists('email', $overrides) ? $overrides['email'] : 'test@example.com');
    $mockUser->shouldReceive('getNickname')->andReturn(array_key_exists('nickname', $overrides) ? $overrides['nickname'] : 'testuser');
    $mockUser->shouldReceive('getAvatar')->andReturn(array_key_exists('avatar', $overrides) ? $overrides['avatar'] : null);

    $mockProvider = Mockery::mock(Provider::class);
    $mockProvider->shouldReceive('user')->andReturn($mockUser);
    $mockProvider->shouldReceive('redirect')->andReturn(redirect('/mocked-redirect'));

    SocialiteFacade::shouldReceive('driver')
        ->with($driver)
        ->andReturn($mockProvider);
}

// ── Livewire Component Tests ──────────────────────────────────────────

test('component renders nothing when no providers are configured', function (): void {
    Livewire::test(Socialite::class)
        ->assertDontSee('Or continue with')
        ->assertDontSee('GitHub');
});

test('component renders buttons for configured providers', function (): void {
    configureSocialiteProvider('github');

    Livewire::test(Socialite::class)
        ->assertSee('Or continue with')
        ->assertSee('GitHub');
});

test('component renders multiple configured providers', function (): void {
    configureSocialiteProvider('github');
    configureSocialiteProvider('google');

    Livewire::test(Socialite::class)
        ->assertSee('GitHub')
        ->assertSee('Google');
});

test('component accepts custom heading prop', function (): void {
    configureSocialiteProvider('github');

    Livewire::test(Socialite::class, ['heading' => 'Sign in with'])
        ->assertSee('Sign in with');
});

test('component hides heading when set to null', function (): void {
    configureSocialiteProvider('github');

    Livewire::test(Socialite::class, ['heading' => null])
        ->assertDontSee('Or continue with');
});

test('component hides labels when showLabels is false', function (): void {
    configureSocialiteProvider('github');

    Livewire::test(Socialite::class, ['showLabels' => false])
        ->assertDontSee('GitHub');
});

// ── Redirect Route Tests ──────────────────────────────────────────────

test('redirect route redirects to provider', function (): void {
    configureSocialiteProvider('github');
    mockSocialiteUser('github');

    $this->get(route('socialite.redirect', 'github'))
        ->assertRedirect();
});

test('invalid provider returns 404 on redirect', function (): void {
    $this->get('/auth/invalid-provider/redirect')
        ->assertNotFound();
});

test('unconfigured provider returns 404 on redirect', function (): void {
    $this->get(route('socialite.redirect', 'github'))
        ->assertNotFound();
});

// ── Callback Route Tests ──────────────────────────────────────────────

test('callback creates a new user and logs them in', function (): void {
    configureSocialiteProvider('github');
    mockSocialiteUser('github', [
        'id' => '99999',
        'name' => 'New Social User',
        'email' => 'social@example.com',
    ]);

    $this->get(route('socialite.callback', 'github'))
        ->assertRedirect('/');

    $user = User::where('email', 'social@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('New Social User')
        ->and($user->socialite_id)->toBe('99999')
        ->and($user->socialite_type)->toBe(SocialiteProvider::Github);

    $this->assertAuthenticatedAs($user);
});

test('callback links to existing user with same email', function (): void {
    configureSocialiteProvider('github');

    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    mockSocialiteUser('github', [
        'id' => '67890',
        'name' => 'Existing User',
        'email' => 'existing@example.com',
    ]);

    $this->get(route('socialite.callback', 'github'))
        ->assertRedirect('/');

    expect(User::count())->toBe(1)
        ->and($existingUser->fresh()->socialite_id)->toBe('67890')
        ->and($existingUser->fresh()->socialite_type)->toBe(SocialiteProvider::Github);

    $this->assertAuthenticatedAs($existingUser);
});

test('callback handles denied authorization gracefully', function (): void {
    configureSocialiteProvider('github');

    $mockProvider = Mockery::mock(Provider::class);
    $mockProvider->shouldReceive('user')->andThrow(new Exception('Access denied'));

    SocialiteFacade::shouldReceive('driver')
        ->with('github')
        ->andReturn($mockProvider);

    $this->get(route('socialite.callback', 'github'))
        ->assertRedirect('/')
        ->assertSessionHas('error');

    $this->assertGuest();
});

test('callback handles missing email gracefully', function (): void {
    configureSocialiteProvider('github');
    mockSocialiteUser('github', [
        'email' => null,
    ]);

    $this->get(route('socialite.callback', 'github'))
        ->assertRedirect('/')
        ->assertSessionHas('error');

    $this->assertGuest();
    expect(User::count())->toBe(0);
});

test('invalid provider returns 404 on callback', function (): void {
    $this->get('/auth/invalid-provider/callback')
        ->assertNotFound();
});

// ── Enum Tests ────────────────────────────────────────────────────────

test('configured providers returns only providers with valid config', function (): void {
    configureSocialiteProvider('github');
    configureSocialiteProvider('google');

    $providers = SocialiteProvider::configuredProviders();

    expect($providers)->toHaveCount(2)
        ->and($providers[0])->toBe(SocialiteProvider::Github)
        ->and($providers[1])->toBe(SocialiteProvider::Google);
});

test('configured providers returns empty array when none configured', function (): void {
    expect(SocialiteProvider::configuredProviders())->toBeEmpty();
});

test('provider is not configured when client_id is missing', function (): void {
    config()->set('services.github', [
        'client_secret' => 'test-secret',
        'redirect' => '/auth/github/callback',
    ]);

    expect(SocialiteProvider::Github->isConfigured())->toBeFalse();
});

test('each provider has label, color, and icon', function (): void {
    foreach (SocialiteProvider::cases() as $provider) {
        expect($provider->label())->toBeString()->not->toBeEmpty()
            ->and($provider->color())->toBeString()->toStartWith('#')
            ->and($provider->icon())->toBeString()->toContain('<svg');
    }
});

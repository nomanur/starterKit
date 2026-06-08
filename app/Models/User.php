<?php

namespace App\Models;

use App\Enums\SocialiteProvider;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use ParagonIE\ConstantTime\Base32;
use Pragmarx\Google2FA\Google2FA;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'socialite_id', 'socialite_type', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable implements FilamentUser, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'socialite_type' => SocialiteProvider::class,
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
        ];
    }

    /**
     * Determine if two-factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_secret !== null 
            && $this->two_factor_confirmed_at !== null;
    }

    /**
     * Generate a new two-factor secret and QR code data.
     *
     * @return array{secret: string, qr_code_url: string}
     */
    public function generateTwoFactorSecret(): array
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        return [
            'secret' => $secret,
            'qr_code_url' => $google2fa->getQRCodeUrl(
                config('app.name'),
                $this->email,
                $secret
            ),
        ];
    }

    /**
     * Verify a TOTP code against the stored secret.
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        if (!$this->hasTwoFactorEnabled()) {
            return false;
        }

        $google2fa = new Google2FA();
        
        try {
            return $google2fa->verifyKey($this->two_factor_secret, $code);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate recovery codes for two-factor authentication.
     *
     * @return array<int, string>
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        
        for ($i = 0; $i < 8; $i++) {
            $codes[] = implode('-', str_split(strtoupper(Base32::encode(random_bytes(10))), 4));
        }
        
        return $codes;
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactorAuthentication(string $secret): void
    {
        $this->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => json_encode($this->generateRecoveryCodes()),
            'two_factor_confirmed_at' => now(),
        ])->save();
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactorAuthentication(): void
    {
        $this->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    /**
     * Get the recovery codes (decrypted).
     *
     * @return array<int, string>|null
     */
    public function getRecoveryCodes(): ?array
    {
        return $this->two_factor_recovery_codes;
    }

    /**
     * Replace a used recovery code.
     */
    public function replaceUsedRecoveryCode(string $usedCode): void
    {
        $codes = $this->getRecoveryCodes() ?? [];
        
        $codes = array_filter($codes, fn ($code) => $code !== $usedCode);
        
        $this->forceFill([
            'two_factor_recovery_codes' => json_encode(array_values($codes)),
        ])->save();
    }
}

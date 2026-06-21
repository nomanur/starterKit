<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallenge extends Page
{
    public function hasLogo(): bool
    {
        return true;
    }

    protected string $view = 'filament.pages.two-factor-challenge';

    protected static string $layout = 'filament-panels::components.layout.simple';

    protected static bool $shouldRegisterNavigation = false;

    public ?string $code = '';

    public ?string $recovery_code = '';

    public bool $useRecoveryCode = false;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Authentication Code')
                    ->placeholder('Enter 6-digit code from your authenticator app')
                    ->length(6)
                    ->numeric()
                    ->required()
                    ->autofocus()
                    ->hidden(fn () => $this->useRecoveryCode),

                TextInput::make('recovery_code')
                    ->label('Recovery Code')
                    ->placeholder('Enter one of your recovery codes')
                    ->required()
                    ->hidden(fn () => ! $this->useRecoveryCode),

                Placeholder::make('instructions')
                    ->content('Open your authenticator app and enter the 6-digit code shown.')
                    ->hidden(fn () => $this->useRecoveryCode),

                Placeholder::make('recovery_instructions')
                    ->content('Lost access to your device? Enter one of your recovery codes instead.')
                    ->hidden(fn () => ! $this->useRecoveryCode),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $user = auth()->user();

        if (! $user) {
            redirect()->route('filament.admin.auth.login');

            return null;
        }

        try {
            $verified = false;

            if ($this->useRecoveryCode && ! empty($this->recovery_code)) {
                $codes = $user->getRecoveryCodes() ?? [];
                $normalizedInput = strtoupper(str_replace('-', '', $this->recovery_code));

                foreach ($codes as $code) {
                    $normalizedCode = str_replace('-', '', $code);
                    if ($normalizedCode === $normalizedInput) {
                        $user->replaceUsedRecoveryCode($code);
                        $verified = true;
                        break;
                    }
                }
            } elseif (! $this->useRecoveryCode && ! empty($this->code)) {
                $google2fa = new Google2FA;
                $verified = $google2fa->verifyKey($user->two_factor_secret, $this->code);
            }

            if ($verified) {
                session()->put('two_factor_verified_at', now());

                redirect()->intended(route('filament.admin.pages.dashboard'));

                return null;
            } else {
                throw new \Exception('Invalid code. Please try again.');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Invalid Code')
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw new Halt;
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('authenticate')
                ->label('Verify')
                ->submit('authenticate')
                ->color('primary'),
        ];
    }

    public function toggleRecoveryCode(): void
    {
        $this->useRecoveryCode = ! $this->useRecoveryCode;
        $this->code = '';
        $this->recovery_code = '';

        $this->resetValidation();
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}

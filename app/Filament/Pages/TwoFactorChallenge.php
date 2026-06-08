<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\View\View;
use Pragmarx\Google2FA\Google2FA;

class TwoFactorChallenge extends BaseLogin
{
    protected static string $view = 'filament.pages.two-factor-challenge';

    public ?string $code = '';

    public ?string $recovery_code = '';

    public bool $useRecoveryCode = false;

    protected function getForms(): array
    {
        return [
            'form' => Form::make()
                ->schema([
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
                        ->hidden(fn () => !$this->useRecoveryCode),

                    Placeholder::make('instructions')
                        ->content('Open your authenticator app and enter the 6-digit code shown.')
                        ->hidden(fn () => $this->useRecoveryCode),

                    Placeholder::make('recovery_instructions')
                        ->content('Lost access to your device? Enter one of your recovery codes instead.')
                        ->hidden(fn () => !$this->useRecoveryCode),
                ])
                ->actions([
                    Action::make('submit')
                        ->label('Verify')
                        ->submit('authenticate'),
                ]),
        ];
    }

    public function authenticate(): void
    {
        $user = session()->get('two_factor_pending_user');

        if (!$user) {
            redirect()->route('filament.admin.auth.login');
            return;
        }

        try {
            $verified = false;

            if ($this->useRecoveryCode && !empty($this->recovery_code)) {
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
            } elseif (!$this->useRecoveryCode && !empty($this->code)) {
                $google2fa = new Google2FA();
                $verified = $google2fa->verifyKey($user->two_factor_secret, $this->code);
            }

            if ($verified) {
                session()->forget('two_factor_pending_user');
                Filament::auth()->login($user, remember: false);
                session()->put('two_factor_verified_at', now());

                redirect()->intended(config('filament.path'));
            } else {
                throw new \Exception('Invalid code. Please try again.');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Invalid Code')
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw new Halt();
        }
    }

    public function toggleRecoveryCode(): void
    {
        $this->useRecoveryCode = !$this->useRecoveryCode;
        $this->code = '';
        $this->recovery_code = '';
        
        $this->resetValidation();
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}

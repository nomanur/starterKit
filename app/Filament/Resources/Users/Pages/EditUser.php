<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Pragmarx\Google2FA\Google2FA;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public ?array $twoFactorData = null;

    public ?string $confirmationCode = '';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('manageTwoFactor')
                ->label(fn ($record) => $record->hasTwoFactorEnabled() ? 'Disable 2FA' : 'Enable 2FA')
                ->icon(fn ($record) => $record->hasTwoFactorEnabled() ? 'heroicon-o-shield-exclamation' : 'heroicon-o-shield-check')
                ->color(fn ($record) => $record->hasTwoFactorEnabled() ? 'danger' : 'success')
                ->modalHeading(fn ($record) => $record->hasTwoFactorEnabled() ? 'Disable Two-Factor Authentication' : 'Enable Two-Factor Authentication')
                ->modalDescription(fn ($record) => $record->hasTwoFactorEnabled() 
                    ? 'This will remove 2FA protection from this account.' 
                    : 'Scan the QR code with your authenticator app to enable 2FA.')
                ->form(function ($record) {
                    if ($record->hasTwoFactorEnabled()) {
                        return [];
                    }

                    $google2fa = new Google2FA();
                    $secret = $google2fa->generateSecretKey();
                    
                    // Store secret temporarily in session for confirmation
                    session()->put('temp_2fa_secret', $secret);

                    return [
                        Placeholder::make('qr_code')
                            ->label('Scan QR Code')
                            ->content('Use Google Authenticator, Authy, or any TOTP app.')
                            ->hintAction(
                                Action::make('show_qr')
                                    ->label('Show QR Code')
                                    ->url($google2fa->getQRCodeUrl(config('app.name'), $record->email, $secret), shouldOpenInNewTab: true)
                            ),
                        Placeholder::make('manual_key')
                            ->label('Manual Entry Key')
                            ->content($secret)
                            ->copyable(),
                        Placeholder::make('instructions')
                            ->label('Next Steps')
                            ->content('After scanning, enter the 6-digit code from your app to confirm.'),
                        \Filament\Forms\Components\TextInput::make('confirmation_code')
                            ->label('Authentication Code')
                            ->length(6)
                            ->numeric()
                            ->required(),
                    ];
                })
                ->action(function ($record, $data) {
                    if ($record->hasTwoFactorEnabled()) {
                        // Disable 2FA
                        $record->disableTwoFactorAuthentication();
                        
                        Notification::make()
                            ->title('2FA Disabled')
                            ->body('Two-factor authentication has been disabled for this user.')
                            ->success()
                            ->send();
                    } else {
                        // Enable 2FA
                        $secret = session()->get('temp_2fa_secret');
                        
                        if (!$secret) {
                            Notification::make()
                                ->title('Error')
                                ->body('Session expired. Please try again.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $google2fa = new Google2FA();
                        $verified = $google2fa->verifyKey($secret, $data['confirmation_code']);

                        if ($verified) {
                            $record->enableTwoFactorAuthentication($secret);
                            session()->forget('temp_2fa_secret');
                            
                            Notification::make()
                                ->title('2FA Enabled')
                                ->body('Two-factor authentication has been successfully enabled.')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Invalid Code')
                                ->body('The code entered is invalid. Please try again.')
                                ->danger()
                                ->send();
                        }
                    }
                }),
        ];
    }
}

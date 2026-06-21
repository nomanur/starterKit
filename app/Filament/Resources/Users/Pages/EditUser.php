<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
use PragmaRX\Google2FA\Google2FA;

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
                ->label(fn (User $record): string => $record->hasTwoFactorEnabled() ? 'Disable 2FA' : 'Enable 2FA')
                ->icon(fn (User $record): string => $record->hasTwoFactorEnabled() ? 'heroicon-o-shield-exclamation' : 'heroicon-o-shield-check')
                ->color(fn (User $record): string => $record->hasTwoFactorEnabled() ? 'danger' : 'success')
                ->modalHeading(fn (User $record): string => $record->hasTwoFactorEnabled() ? 'Disable Two-Factor Authentication' : 'Enable Two-Factor Authentication')
                ->modalDescription(fn (User $record): string => $record->hasTwoFactorEnabled()
                    ? 'This will remove 2FA protection from this account.'
                    : 'Scan the QR code with your authenticator app to enable 2FA.')
                ->form(function (User $record): array {
                    if ($record->hasTwoFactorEnabled()) {
                        return [];
                    }

                    return [
                        Hidden::make('two_factor_secret')
                            ->default(fn (): string => (new Google2FA)->generateSecretKey()),
                        Placeholder::make('qr_code')
                            ->label('Scan QR Code')
                            ->content(function (Get $get) use ($record): HtmlString|string {
                                $secret = $get('two_factor_secret');
                                if (! $secret) {
                                    return '';
                                }
                                $google2fa = new Google2FA;
                                $qrCodeUrl = $google2fa->getQRCodeUrl(config('app.name'), $record->email, $secret);
                                $renderer = new ImageRenderer(
                                    new RendererStyle(200),
                                    new SvgImageBackEnd
                                );
                                $writer = new Writer($renderer);
                                $qrCodeSvg = $writer->writeString($qrCodeUrl);

                                return new HtmlString(
                                    '<div class="flex justify-center p-2 bg-white rounded-lg" style="width: 216px; height: 216px; margin: 0 auto; border: 1px solid #e5e7eb;">'.
                                    $qrCodeSvg.
                                    '</div>'
                                );
                            }),
                        Placeholder::make('manual_key')
                            ->label('Manual Entry Key')
                            ->content(fn (Get $get): ?string => $get('two_factor_secret'))
                            ->copyable(),
                        Placeholder::make('instructions')
                            ->label('Next Steps')
                            ->content('Scan the QR code with Google Authenticator, Authy, or any TOTP app, then enter the 6-digit code from your app to confirm.'),
                        TextInput::make('confirmation_code')
                            ->label('Authentication Code')
                            ->length(6)
                            ->numeric()
                            ->required(),
                    ];
                })
                ->action(function (User $record, array $data): void {
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
                        $secret = $data['two_factor_secret'] ?? null;

                        if (! $secret) {
                            Notification::make()
                                ->title('Error')
                                ->body('Failed to retrieve the 2FA secret. Please try again.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $google2fa = new Google2FA;
                        $verified = $google2fa->verifyKey($secret, $data['confirmation_code']);

                        if ($verified) {
                            $record->enableTwoFactorAuthentication($secret);

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

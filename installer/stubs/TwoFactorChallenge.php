<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;

class TwoFactorChallenge extends SimplePage
{
    use WithRateLimiting;

    protected static string $view = 'filament.pages.two-factor-challenge';

    public ?string $code = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Authentication Code')
                    ->required()
                    ->numeric()
                    ->rule('digits:6'),
            ]);
    }

    public function submit(): void
    {
        try {
            $this->rateLimit(5, 60);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title("Too many attempts. Please try again in {$exception->secondsUntilAvailable} seconds.")
                ->danger()
                ->send();

            return;
        }

        $user = Filament::auth()->user();

        if (! $user->verifyTwoFactorCode($this->code)) {
            $this->addError('code', 'The provided code is invalid.');

            return;
        }

        session()->put('two_factor_verified_at_'.$user->id, now()->timestamp);

        $this->redirect(Filament::getUrl());
    }

    protected function getRateLimitKey(): string
    {
        return 'two-factor-challenge:'.Filament::auth()->id();
    }
}

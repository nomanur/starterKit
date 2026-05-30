<?php

namespace App\Livewire;

use App\Enums\SocialiteProvider;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Socialite extends Component
{
    public ?string $heading = 'Or continue with';

    public bool $showLabels = true;

    public int $columns = 1;

    public string $size = 'md';

    public function render(): View
    {
        return view('livewire.socialite', [
            'providers' => SocialiteProvider::configuredProviders(),
            'gridClass' => $this->gridClass(),
            'buttonClass' => $this->buttonClass(),
        ]);
    }

    private function gridClass(): string
    {
        return match ($this->columns) {
            2 => 'grid-cols-2',
            3 => 'grid-cols-3',
            4 => 'grid-cols-4',
            default => 'grid-cols-1',
        };
    }

    private function buttonClass(): string
    {
        return match ($this->size) {
            'sm' => 'py-2 px-3 text-xs gap-2',
            'lg' => 'py-3.5 px-5 text-base gap-3',
            default => 'py-2.5 px-4 text-sm gap-2.5',
        };
    }
}

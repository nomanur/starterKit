<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Test extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $photo = null;

    public ?User $user = null;

    public ?string $successMessage = null;

    public function mount(): void
    {
        $this->user = User::first() ?? User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function save(): void
    {
        $this->validate([
            'photo' => ['required', 'image', 'max:5120'], // Max 5MB
        ]);

        if ($this->user && $this->photo) {
            $this->user->addMedia($this->photo->getRealPath())
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('avatars');

            $this->photo = null;
            $this->successMessage = 'Photo uploaded and saved to Spatie Media Library successfully!';
        }
    }

    public function deleteAvatar(): void
    {
        if ($this->user) {
            $this->user->clearMediaCollection('avatars');
            $this->successMessage = 'Avatar deleted successfully!';
        }
    }

    public function render(): View
    {
        $avatar = $this->user ? $this->user->getFirstMedia('avatars') : null;

        return view('livewire.test', [
            'avatar' => $avatar,
        ]);
    }
}

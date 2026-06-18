<x-filament-panels::page.simple>
    <form wire:submit="authenticate" class="grid gap-y-6">
        {{ $this->form }}

        <div class="fi-form-actions">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>

    <div class="text-center mt-4">
        <x-filament::link
            tag="button"
            type="button"
            wire:click="toggleRecoveryCode"
            color="primary"
            size="sm"
        >
            {{ $this->useRecoveryCode ? 'Use an authentication code' : 'Use a recovery code' }}
        </x-filament::link>
    </div>
</x-filament-panels::page.simple>

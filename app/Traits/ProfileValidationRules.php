<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'required',
            'min:8',
            'confirmed',
        ];
    }
}

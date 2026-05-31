<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines\Pages;

use App\Filament\Resources\LanguageLines\LanguageLineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLanguageLine extends CreateRecord
{
    protected static string $resource = LanguageLineResource::class;
}

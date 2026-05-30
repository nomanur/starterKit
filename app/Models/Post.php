<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'content'];

    public array $translatable = ['title', 'content'];
}

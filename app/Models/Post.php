<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nomanur\FilamentSeoPro\Traits\HasSeo;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasTranslations, HasSeo;

    protected $fillable = ['title', 'content'];

    public array $translatable = ['title', 'content'];
}

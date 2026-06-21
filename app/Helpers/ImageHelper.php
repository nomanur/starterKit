<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class ImageHelper
{
    /**
     * Get user avatar URL with fallback to default.
     */
    public static function getAvatar(mixed $user, int $size = 100): string
    {
        if (! $user) {
            return self::getDefaultAvatar($size);
        }

        // Check if user has an avatar attribute
        if (isset($user->avatar) && $user->avatar) {
            return self::storageUrl($user->avatar);
        }

        // Check for email to use Gravatar
        if (isset($user->email)) {
            return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($user->email))).'?s='.$size.'&d=mp';
        }

        return self::getDefaultAvatar($size);
    }

    /**
     * Get default avatar URL.
     */
    public static function getDefaultAvatar(int $size = 100): string
    {
        return asset('images/default-avatar.png');
    }

    /**
     * Get a safe storage URL for a file.
     */
    public static function storageUrl(?string $path): string
    {
        if (! $path) {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::url($path);
    }

    /**
     * Convert bytes to human-readable file size.
     */
    public static function fileSizeHuman(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    /**
     * Upload and process an image file.
     */
    public static function uploadImage(UploadedFile $file, string $directory, array $options = []): ?string
    {
        if (! $file->isValid()) {
            return null;
        }

        $maxWidth = $options['max_width'] ?? 1920;
        $maxHeight = $options['max_height'] ?? 1080;
        $quality = $options['quality'] ?? 85;

        try {
            // Generate unique filename
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $path = trim($directory, '/').'/'.$filename;

            // Store original file temporarily on public disk
            $file->storeAs(trim($directory, '/'), $filename, 'public');
            $fullPath = Storage::disk('public')->path($path);

            // Manipulate using Spatie Image
            Image::load($fullPath)
                ->width($maxWidth)
                ->height($maxHeight)
                ->quality($quality)
                ->save();

            return $path;
        } catch (\Exception $e) {
            Log::error('Image upload failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Delete an image from storage.
     */
    public static function deleteImage(?string $path): bool
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    /**
     * Get image dimensions.
     */
    public static function getDimensions(string $path): ?array
    {
        try {
            $fullPath = Storage::disk('public')->path($path);

            if (! file_exists($fullPath)) {
                return null;
            }

            $size = getimagesize($fullPath);

            if ($size) {
                return [
                    'width' => $size[0],
                    'height' => $size[1],
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to get image dimensions: '.$e->getMessage());
        }

        return null;
    }
}

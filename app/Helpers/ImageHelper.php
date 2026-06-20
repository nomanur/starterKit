<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    /**
     * Get user avatar URL with fallback to default.
     *
     * @param mixed $user
     * @param int $size
     * @return string
     */
    public static function getAvatar($user, int $size = 100): string
    {
        if (!$user) {
            return self::getDefaultAvatar($size);
        }

        // Check if user has an avatar attribute
        if (isset($user->avatar) && $user->avatar) {
            return self::storageUrl($user->avatar);
        }

        // Check for email to use Gravatar
        if (isset($user->email)) {
            return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=' . $size . '&d=mp';
        }

        return self::getDefaultAvatar($size);
    }

    /**
     * Get default avatar URL.
     *
     * @param int $size
     * @return string
     */
    public static function getDefaultAvatar(int $size = 100): string
    {
        return asset('images/default-avatar.png');
    }

    /**
     * Get a safe storage URL for a file.
     *
     * @param string|null $path
     * @return string
     */
    public static function storageUrl(?string $path): string
    {
        if (!$path) {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::url($path);
    }

    /**
     * Convert bytes to human-readable file size.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function fileSizeHuman(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Upload and process an image file.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return string|null
     */
    public static function uploadImage(UploadedFile $file, string $directory, array $options = []): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $maxWidth = $options['max_width'] ?? 1920;
        $maxHeight = $options['max_height'] ?? 1080;
        $quality = $options['quality'] ?? 85;

        try {
            $image = Image::make($file->getRealPath());
            
            // Resize if needed
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Generate unique filename
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = trim($directory, '/') . '/' . $filename;

            // Save to storage
            Storage::disk('public')->put($path, $image->encode(null, $quality)->getEncoded());

            return $path;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $path
     * @return bool
     */
    public static function deleteImage(?string $path): bool
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    /**
     * Get image dimensions.
     *
     * @param string $path
     * @return array|null
     */
    public static function getDimensions(string $path): ?array
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            
            if (!file_exists($fullPath)) {
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
            \Log::error('Failed to get image dimensions: ' . $e->getMessage());
        }

        return null;
    }
}

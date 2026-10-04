<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ImageUrl
{
    public static function placeholder(): string
    {
        return asset('assets/images/image-placeholder.svg');
    }

    public static function resolve(?string $image): string
    {
        $image = trim($image ?? '');

        if ($image === '') {
            return self::placeholder();
        }

        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        $image = ltrim($image, '/');

        if (str_starts_with($image, 'assets/')) {
            return is_file(public_path($image)) ? asset($image) : self::placeholder();
        }

        $path = str_starts_with($image, 'storage/') ? substr($image, 8) : $image;

        return Storage::disk('public')->exists($path)
            ? asset('storage/' . $path)
            : self::placeholder();
    }
}

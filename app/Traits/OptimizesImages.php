<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait OptimizesImages
{
    protected function storeOptimizedImage(UploadedFile $file, string $folder, int $maxWidth = 1920, int $quality = 82): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
            return $file->store($folder, 'public');
        }

        $image = match ($mime) {
            'image/png'  => @imagecreatefrompng($file->getRealPath()),
            'image/webp' => @imagecreatefromwebp($file->getRealPath()),
            default      => @imagecreatefromjpeg($file->getRealPath()),
        };

        if (!$image) {
            return $file->store($folder, 'public');
        }

        $origW = imagesx($image);
        $origH = imagesy($image);

        if ($origW > $maxWidth) {
            $newW = $maxWidth;
            $newH = (int) round($origH * ($maxWidth / $origW));
            $resized = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($image);
            $image = $resized;
        }

        $filename = $folder . '/' . Str::random(40) . '.jpg';
        $tempPath = tempnam(sys_get_temp_dir(), 'img');

        imagejpeg($image, $tempPath, $quality);
        imagedestroy($image);

        Storage::disk('public')->put($filename, file_get_contents($tempPath));
        @unlink($tempPath);

        return $filename;
    }
}

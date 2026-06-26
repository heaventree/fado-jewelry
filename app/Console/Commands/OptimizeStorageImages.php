<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeStorageImages extends Command
{
    protected $signature = 'fado:optimize-images {folder=sliders} {--quality=82} {--max-width=1920} {--dry-run}';
    protected $description = 'Convert existing PNG/large images in storage to optimized JPEGs';

    public function handle(): int
    {
        $folder = $this->argument('folder');
        $quality = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');
        $dryRun = $this->option('dry-run');

        $files = Storage::disk('public')->files($folder);
        $this->info("Found " . count($files) . " files in {$folder}/");

        $saved = 0;

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                continue;
            }

            $fullPath = Storage::disk('public')->path($file);
            $originalSize = filesize($fullPath);
            $sizeKB = round($originalSize / 1024);

            if ($ext === 'jpg' || $ext === 'jpeg') {
                if ($originalSize < 500 * 1024) {
                    $this->line("  SKIP {$file} ({$sizeKB} KB — already small)");
                    continue;
                }
            }

            $this->line("  Processing {$file} ({$sizeKB} KB)...");

            if ($dryRun) {
                continue;
            }

            $image = match ($ext) {
                'png'  => @imagecreatefrompng($fullPath),
                'webp' => @imagecreatefromwebp($fullPath),
                default => @imagecreatefromjpeg($fullPath),
            };

            if (!$image) {
                $this->warn("  Could not read {$file}");
                continue;
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

            $newFile = preg_replace('/\.(png|webp|jpe?g)$/i', '.jpg', $file);
            $newFullPath = Storage::disk('public')->path($newFile);

            imagejpeg($image, $newFullPath, $quality);
            imagedestroy($image);

            $newSize = filesize($newFullPath);
            $newSizeKB = round($newSize / 1024);
            $reduction = round((1 - $newSize / $originalSize) * 100);

            if ($newFile !== $file) {
                @unlink($fullPath);

                $model = \App\Models\Slider::where('image', $file)->first()
                    ?? \App\Models\Collection::where('banner_image', $file)->first()
                    ?? \App\Models\Category::where('banner_image', $file)->first();

                if ($model) {
                    $col = $model instanceof \App\Models\Slider ? 'image' : 'banner_image';
                    $model->update([$col => $newFile]);
                }
            }

            $this->info("  ✓ {$newFile} — {$newSizeKB} KB (saved {$reduction}%)");
            $saved++;
        }

        $this->info("Done. Optimized {$saved} images.");

        return 0;
    }
}

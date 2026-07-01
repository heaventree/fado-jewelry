<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * One-off stopgap: the theme's fallback category images (cate-26/27/28.jpg)
 * are blank placeholder swatches, not real photos, so the Pendants/Earrings/
 * Bracelets & Bangles homepage tiles render empty. This copies an existing
 * real demo product photo in as each category's thumbnail_image until the
 * client uploads real photography via Admin > Categories.
 */
class SeedCategoryThumbnailsStopgap extends Command
{
    protected $signature = 'fado:seed-category-thumbnails-stopgap';
    protected $description = 'Fill blank Pendants/Earrings/Bracelets & Bangles homepage tiles with an existing demo product photo';

    private array $map = [
        'pendants'           => 'claddagh-pendant-1.jpg',
        'earrings'           => 'shamrock-stud-earrings-1.jpg',
        'bracelets-bangles'  => 'livia-bangle-1.jpg',
    ];

    public function handle(): int
    {
        foreach ($this->map as $slug => $demoFile) {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                $this->warn("Skipped: no category with slug \"{$slug}\"");
                continue;
            }

            if ($category->thumbnail_image) {
                $this->line("Skipped \"{$category->name}\" — thumbnail_image already set.");
                continue;
            }

            $sourcePath = public_path('images/products/demo/' . $demoFile);

            if (!file_exists($sourcePath)) {
                $this->error("Skipped \"{$category->name}\" — {$sourcePath} not found.");
                continue;
            }

            $destPath = 'categories/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($destPath, file_get_contents($sourcePath));

            $category->update(['thumbnail_image' => $destPath]);

            $this->info("Set \"{$category->name}\" thumbnail to {$demoFile}.");
        }

        return self::SUCCESS;
    }
}

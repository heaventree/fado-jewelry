<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportRealImages extends Command
{
    protected $signature = 'fado:import-real-images
                            {--source= : Path to the _opencart-images/image directory (defaults to _opencart-images/image inside the project root)}
                            {--dry-run : Parse and log without writing anything to the database or storage}';

    protected $description = 'Import real product images from the _opencart-images/image directory';

    // Folder name → Collection slug (null = no collection, images still imported)
    private const COLLECTION_MAP = [
        'An Ri'              => 'an-ri',
        'Claddagh Mo Chroi'  => 'claddagh',
        'Celtic Crosses'     => 'high-crosses',
        'Ogham Celtic Cross' => 'high-crosses',
        'Livia'              => 'livia',
        'Sheelin'            => 'sheelin',
        'Trinity'            => 'trinity',
        'Avoca'              => 'claddagh',
        'Celtic Rose'        => null,
        'Band Rings'         => null,
        'Silver'             => null,
        'Two Tone'           => null,
        'White Back'         => null, // hero/primary images matched by SKU
        'Others'             => null,
    ];

    // Metal keyword (lowercase) → canonical Metal name — order matters, longest match wins
    private const METAL_KEYWORDS = [
        'two-tone'   => 'Two-tone',
        'two tone'   => 'Two-tone',
        'white gold' => '9ct White Gold',
        'white-gold' => '9ct White Gold',
        'rose gold'  => '9ct Rose Gold',
        'rose-gold'  => '9ct Rose Gold',
        'gold'       => '9ct Yellow Gold',
        'silver'     => 'Sterling Silver',
    ];

    // Product type keywords (lowercase) → label — longest match first
    private const TYPE_KEYWORDS = [
        'drop earrings'  => 'Earrings',
        'stud earrings'  => 'Earrings',
        'bar brooch'     => 'Brooch',
        'cufflinks'      => 'Cufflinks',
        'bracelet'       => 'Bracelet',
        'pendant'        => 'Pendant',
        'earrings'       => 'Earrings',
        'brooch'         => 'Brooch',
        'bangle'         => 'Bracelet',
        'cross'          => 'Cross',
        'band ring'      => 'Ring',
        'ring'           => 'Ring',
        'band'           => 'Ring',
    ];

    private string $logFile;
    private bool   $dryRun = false;

    private int $created  = 0;
    private int $updated  = 0;
    private int $skipped  = 0;
    private int $unparsed = 0;
    private int $images   = 0;

    public function handle(): int
    {
        $this->dryRun  = (bool) $this->option('dry-run');
        $this->logFile = storage_path('logs/image-import.log');

        $source = rtrim(
            $this->option('source') ?: base_path('_opencart-images/image'),
            '/\\'
        );

        if (! is_dir($source)) {
            $this->error("Source directory not found: {$source}");
            return self::FAILURE;
        }

        if ($this->dryRun) {
            $this->warn('DRY RUN — nothing will be written to the database or storage.');
        }

        $this->writelog('=== Image import started ' . now() . ' ===');

        // Process collection sub-folders
        $entries = scandir($source);
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            if (is_dir("{$source}/{$entry}")) {
                $this->processFolder("{$source}/{$entry}", $entry);
            }
        }

        // Process any images sitting directly in the root
        $rootImages = array_filter($entries, fn ($f) => is_file("{$source}/{$f}") && $this->isImage($f));
        if (count($rootImages)) {
            $this->info("\nRoot-level images: " . count($rootImages));
            foreach ($rootImages as $filename) {
                $this->processImage("{$source}/{$filename}", $filename, null, false);
            }
        }

        $this->info('');
        $this->table(
            ['Created', 'Updated', 'Skipped', 'Unparseable', 'Images saved'],
            [[$this->created, $this->updated, $this->skipped, $this->unparsed, $this->images]]
        );
        $this->info("Log: {$this->logFile}");
        $this->writelog('=== Import finished ' . now() . ' ===');

        return self::SUCCESS;
    }

    // -------------------------------------------------------------------------

    private function processFolder(string $path, string $folderName): void
    {
        $collectionSlug = self::COLLECTION_MAP[$folderName] ?? null;
        $collection     = $collectionSlug ? Collection::where('slug', $collectionSlug)->first() : null;
        $isHeroFolder   = $folderName === 'White Back';

        $images = array_filter(scandir($path), fn ($f) => is_file("{$path}/{$f}") && $this->isImage($f));

        $this->info("\nFolder: <fg=yellow>{$folderName}</> (" . count($images) . ' images)'
            . ($collection ? " → collection: {$collection->name}" : ''));

        foreach ($images as $filename) {
            $this->processImage("{$path}/{$filename}", $filename, $collection, $isHeroFolder);
        }
    }

    private function processImage(
        string $filepath,
        string $filename,
        ?Collection $collection,
        bool $isHeroShot
    ): void {
        $parsed = $this->parseFilename($filename);

        if (! $parsed) {
            $this->unparsed++;
            $this->writelog("UNPARSED | {$filename} | Could not extract product name or type");
            $this->line("  <comment>SKIP (unparseable)</comment> {$filename}");
            return;
        }

        ['name' => $productName, 'metal' => $metalName, 'type' => $type, 'sku' => $sku] = $parsed;

        // Attempt to match existing product: SKU → exact name → slug
        $product = null;
        if ($sku) {
            $product = Product::where('name', 'LIKE', "%{$sku}%")->first();
        }
        if (! $product) {
            $product = Product::where('name', $productName)->first();
        }

        if ($this->dryRun) {
            $tag = $product ? 'UPDATE' : 'CREATE';
            $this->line("  [{$tag}] {$productName}" . ($sku ? " (SKU:{$sku})" : '') . " | {$metalName}" . ($type ? " | {$type}" : ''));
            return;
        }

        // Create product if it doesn't exist
        if (! $product) {
            $slug = $this->uniqueSlug(Str::slug($productName));
            $product = Product::create([
                'name'      => $productName,
                'slug'      => $slug,
                'is_active' => true,
            ]);
            $this->created++;
            $this->line("  <info>CREATED</info> {$productName}");
        } else {
            $this->updated++;
            $this->line("  <comment>UPDATED</comment> {$productName}");
        }

        // Attach collection
        if ($collection && ! $product->collections()->where('collection_id', $collection->id)->exists()) {
            $product->collections()->attach($collection->id);
        }

        // Ensure a variant exists for this metal
        $metal = Metal::where('name', $metalName)->orWhere('name', 'LIKE', "%{$metalName}%")->first();
        if ($metal && ! $product->allVariants()->where('metal_id', $metal->id)->exists()) {
            ProductVariant::create([
                'product_id' => $product->id,
                'metal_id'   => $metal->id,
                'sku'        => $sku,
                'stock'      => 0,
                'is_active'  => true,
            ]);
        }

        // Copy image to storage/app/public/products/{id}/
        $ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $destName = Str::slug(pathinfo($filename, PATHINFO_FILENAME)) . '.' . $ext;
        $destPath = "products/{$product->id}/{$destName}";

        Storage::disk('public')->makeDirectory("products/{$product->id}");

        if (! Storage::disk('public')->exists($destPath)) {
            Storage::disk('public')->put($destPath, file_get_contents($filepath));
            $this->images++;
        }

        // Create ProductImage record (hero shots become primary; otherwise primary only if none exists)
        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $isPrimary  = $isHeroShot || ! $hasPrimary;

        ProductImage::firstOrCreate(
            ['product_id' => $product->id, 'path' => $destPath],
            [
                'is_primary'  => $isPrimary,
                'sort_order'  => $product->images()->count(),
            ]
        );

        if ($isPrimary && ! $isHeroShot) {
            // Ensure only one primary per product
            $product->images()->where('path', '!=', $destPath)->update(['is_primary' => false]);
        }
    }

    // -------------------------------------------------------------------------
    // Filename parsing
    // -------------------------------------------------------------------------

    private function parseFilename(string $filename): ?array
    {
        $base  = pathinfo($filename, PATHINFO_FILENAME);
        $lower = strtolower(str_replace(['-', '_'], ' ', urldecode($base)));

        // Extract SKU: uppercase letters (1-3) followed by 3-4 digits and optional suffix
        $sku = null;
        if (preg_match('/\b([A-Z]{1,3}\d{3,4}[A-Z]?\d?)\b/', $base, $m)) {
            $sku = $m[1];
        }

        // Extract metal
        $metalName = 'Sterling Silver';
        foreach (self::METAL_KEYWORDS as $keyword => $metal) {
            if (str_contains($lower, $keyword)) {
                $metalName = $metal;
                break;
            }
        }

        // Extract product type
        $type = null;
        foreach (self::TYPE_KEYWORDS as $keyword => $label) {
            if (str_contains($lower, $keyword)) {
                $type = $label;
                break;
            }
        }

        // Build cleaned product name
        $name = str_replace(['-', '_'], ' ', urldecode($base));
        $name = preg_replace('/\bIrish\b/i', '', $name);

        // Remove metal keywords
        foreach (array_keys(self::METAL_KEYWORDS) as $kw) {
            $name = preg_replace('/\b' . preg_quote(ucwords($kw), '/') . '\b/i', '', $name);
        }

        // Remove SKU
        if ($sku) {
            $name = preg_replace('/\b' . preg_quote($sku, '/') . '\b/', '', $name);
        }

        // Remove trailing numbers/sizes
        $name = preg_replace('/\b\d{1,2}\s*Karat\b/i', '', $name);
        $name = preg_replace('/\s*-{2,}\s*/', ' ', $name);
        $name = preg_replace('/\s+/', ' ', trim($name, ' -–'));

        if (strlen($name) < 3) {
            return null;
        }

        // Append metal to name to differentiate gold vs silver variants
        $displayName = $name;
        if ($metalName !== 'Sterling Silver') {
            $displayName = rtrim($name, ' ') . ' — ' . $metalName;
        }

        return [
            'name'  => $displayName,
            'metal' => $metalName,
            'type'  => $type,
            'sku'   => $sku,
        ];
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function isImage(string $filename): bool
    {
        return (bool) preg_match('/\.(jpe?g|png|webp|gif)$/i', $filename);
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i    = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function writelog(string $message): void
    {
        file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND);
    }
}

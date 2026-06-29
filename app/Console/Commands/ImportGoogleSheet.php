<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Gemstone;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportGoogleSheet extends Command
{
    protected $signature = 'fado:import-sheet
                            {file : Path to the CSV file exported from Google Sheets}
                            {--dry-run : Parse and log without writing to the database}
                            {--image-path=products : Sub-path under public/ where images are stored}';

    protected $description = 'Import products and variants from a Google Sheets CSV export, grouping rows by Product Name';

    private string $logFile;
    private bool $dryRun = false;
    private array $metals = [];
    private array $gemstones = [];
    private array $categories = [];
    private array $collections = [];

    private int $productsCreated = 0;
    private int $variantsCreated = 0;
    private int $sizesCreated = 0;
    private int $imagesCreated = 0;
    private int $rowsSkipped = 0;
    private int $warnings = 0;

    public function handle(): int
    {
        $this->logFile = storage_path('logs/sheet-import.log');
        $this->dryRun = (bool) $this->option('dry-run');
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        if ($this->dryRun) {
            $this->warn('DRY RUN — nothing will be written to the database.');
        }

        $this->log('=== Google Sheet import started ' . now() . ' ===');

        $this->loadLookups();

        $rows = $this->parseCsv($file);
        if ($rows === null) {
            return self::FAILURE;
        }

        $grouped = $this->groupByProductName($rows);

        $this->output->progressStart(count($grouped));

        foreach ($grouped as $productName => $productRows) {
            try {
                $this->importProductGroup($productName, $productRows);
            } catch (\Throwable $e) {
                $this->rowsSkipped += count($productRows);
                $this->log("ERROR [{$productName}]: " . $e->getMessage());
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->summary();
        $this->log('=== Import finished ' . now() . ' ===');

        return self::SUCCESS;
    }

    private function loadLookups(): void
    {
        $this->metals = Metal::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [
            Str::lower($name) => $id,
        ])->all();

        $this->gemstones = Gemstone::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [
            Str::lower($name) => $id,
        ])->all();

        $this->categories = Category::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [
            Str::lower($name) => $id,
        ])->all();

        $this->collections = Collection::pluck('id', 'name')->mapWithKeys(fn ($id, $name) => [
            Str::lower($name) => $id,
        ])->all();
    }

    private function parseCsv(string $file): ?array
    {
        $handle = fopen($file, 'r');
        if (! $handle) {
            $this->error("Cannot open file: {$file}");
            return null;
        }

        $header = fgetcsv($handle);
        if (! $header) {
            $this->error('CSV file is empty or has no header row.');
            fclose($handle);
            return null;
        }

        $header = array_map('trim', $header);

        $required = ['Product Name', 'SKU', 'Price EUR'];
        $missing = array_diff($required, $header);
        if ($missing) {
            $this->error('Missing required columns: ' . implode(', ', $missing));
            fclose($handle);
            return null;
        }

        $rows = [];
        $lineNum = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $lineNum++;
            if (count($data) !== count($header)) {
                $this->log("SKIP line {$lineNum}: column count mismatch (expected " . count($header) . ", got " . count($data) . ')');
                $this->rowsSkipped++;
                continue;
            }
            $rows[] = array_combine($header, array_map('trim', $data));
        }

        fclose($handle);
        $this->info("Parsed " . count($rows) . " data rows from CSV.");

        return $rows;
    }

    private function groupByProductName(array $rows): array
    {
        $grouped = [];
        foreach ($rows as $row) {
            $name = $row['Product Name'] ?? '';
            if ($name === '') {
                $this->rowsSkipped++;
                $this->log('SKIP row with empty Product Name');
                continue;
            }
            $grouped[$name][] = $row;
        }
        return $grouped;
    }

    private function importProductGroup(string $productName, array $rows): void
    {
        $first = $rows[0];

        $slug = $this->resolveSlug($first, $productName);
        $description = $first['Description'] ?? '';
        $shortDescription = $first['Short_description'] ?? '';
        $isActive = $this->parseBool($first['is_active'] ?? '1');

        // Warn if later rows disagree on product-level fields
        for ($i = 1; $i < count($rows); $i++) {
            $r = $rows[$i];
            if (($r['Slug'] ?? '') !== '' && Str::slug($r['Slug']) !== $slug) {
                $this->warn("  [{$productName}] row " . ($i + 1) . " has different Slug — using first row's value");
                $this->warnings++;
            }
            if (($r['Description'] ?? '') !== '' && ($r['Description'] ?? '') !== $description) {
                $this->warn("  [{$productName}] row " . ($i + 1) . " has different Description — using first row's value");
                $this->warnings++;
            }
        }

        if ($this->dryRun) {
            $this->line("  [DRY] Product: {$productName} → /{$slug} (" . count($rows) . " variant rows)");
            $this->productsCreated++;
            $this->variantsCreated += count($rows);
            return;
        }

        $product = Product::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $productName,
                'slug' => $slug,
                'description' => $description,
                'short_description' => $shortDescription,
                'is_active' => $isActive,
            ]
        );

        if ($product->wasRecentlyCreated) {
            $this->productsCreated++;
        }

        // Categories — combine from all rows, deduplicate
        $this->attachCategories($product, $rows);

        // Collections — combine from all rows, deduplicate
        $this->attachCollections($product, $rows);

        // Ring sizes — from first row that has them
        $this->importSizes($product, $rows);

        // Images — from first row that has them
        $this->importImages($product, $rows);

        // Variants — one per row
        foreach ($rows as $row) {
            $this->createVariant($product, $row);
        }
    }

    private function resolveSlug(array $row, string $productName): string
    {
        $slug = ! empty($row['Slug']) ? Str::slug($row['Slug']) : Str::slug($productName);
        if (! $slug) {
            $slug = 'product-' . Str::random(6);
        }

        $original = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    private function createVariant(Product $product, array $row): void
    {
        $metalId = $this->lookupId($this->metals, $row['Metal'] ?? '', 'Metal', $product->name);
        if (! $metalId) {
            $this->rowsSkipped++;
            return;
        }

        $secondMetalId = $this->lookupId($this->metals, $row['Second Metal'] ?? '', 'Second Metal', $product->name, true);
        $gemstoneId = $this->lookupId($this->gemstones, $row['Gemstone'] ?? '', 'Gemstone', $product->name, true);
        $colour = $row['Colour'] ?? null;
        $colour = $colour !== '' ? $colour : null;

        $variant = ProductVariant::firstOrCreate(
            [
                'product_id' => $product->id,
                'metal_id' => $metalId,
                'second_metal_id' => $secondMetalId,
                'gemstone_id' => $gemstoneId,
                'colour' => $colour,
            ],
            [
                'sku' => $row['SKU'] ?? null,
                'price_eur' => (float) ($row['Price EUR'] ?? 0),
                'stock' => (int) ($row['Stock'] ?? 0),
                'is_active' => $this->parseBool($row['is_active'] ?? '1'),
            ]
        );

        if ($variant->wasRecentlyCreated) {
            $this->variantsCreated++;
        }
    }

    private function attachCategories(Product $product, array $rows): void
    {
        $ids = [];
        foreach ($rows as $row) {
            $names = $this->splitMulti($row['Category'] ?? '');
            foreach ($names as $name) {
                $id = $this->categories[Str::lower($name)] ?? null;
                if ($id) {
                    $ids[] = $id;
                } elseif ($name !== '') {
                    $this->log("WARN [{$product->name}]: category '{$name}' not found in database");
                    $this->warnings++;
                }
            }
        }
        if ($ids) {
            $product->categories()->syncWithoutDetaching(array_unique($ids));
        }
    }

    private function attachCollections(Product $product, array $rows): void
    {
        $ids = [];
        foreach ($rows as $row) {
            $names = $this->splitMulti($row['Collection'] ?? '');
            foreach ($names as $name) {
                $id = $this->collections[Str::lower($name)] ?? null;
                if ($id) {
                    $ids[] = $id;
                } elseif ($name !== '') {
                    $this->log("WARN [{$product->name}]: collection '{$name}' not found in database");
                    $this->warnings++;
                }
            }
        }
        if ($ids) {
            $product->collections()->syncWithoutDetaching(array_unique($ids));
        }
    }

    private function importSizes(Product $product, array $rows): void
    {
        foreach ($rows as $row) {
            $raw = $row['Ring Sizes'] ?? '';
            if ($raw === '') {
                continue;
            }

            $sizes = array_filter(array_map('trim', explode(',', $raw)));
            foreach ($sizes as $size) {
                if (! is_numeric($size)) {
                    $this->log("WARN [{$product->name}]: invalid ring size '{$size}'");
                    $this->warnings++;
                    continue;
                }
                $created = ProductSize::firstOrCreate(
                    ['product_id' => $product->id, 'us_size' => (float) $size],
                    ['stock' => 0]
                );
                if ($created->wasRecentlyCreated) {
                    $this->sizesCreated++;
                }
            }
            break; // sizes are product-level, only process the first row that has them
        }
    }

    private function importImages(Product $product, array $rows): void
    {
        $imagePath = $this->option('image-path');

        foreach ($rows as $row) {
            $raw = $row['Image_filenames'] ?? '';
            if ($raw === '') {
                continue;
            }

            $filenames = array_filter(array_map('trim', explode(',', $raw)));
            $isPrimary = ! $product->images()->exists();

            foreach ($filenames as $i => $filename) {
                $path = $imagePath . '/' . $filename;

                $image = ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'path' => $path],
                    ['sort_order' => $i, 'is_primary' => $isPrimary]
                );

                if ($image->wasRecentlyCreated) {
                    $this->imagesCreated++;
                }
                $isPrimary = false;
            }
            break; // images are product-level, only process the first row that has them
        }
    }

    private function lookupId(array $lookup, string $name, string $type, string $productName, bool $optional = false): ?int
    {
        if ($name === '') {
            if (! $optional) {
                $this->log("WARN [{$productName}]: required {$type} is empty — skipping variant");
                $this->warnings++;
            }
            return null;
        }

        $id = $lookup[Str::lower($name)] ?? null;
        if (! $id) {
            $this->log("WARN [{$productName}]: {$type} '{$name}' not found in database" . ($optional ? ' — ignored' : ' — skipping variant'));
            $this->warnings++;
            return $optional ? null : null;
        }

        return $id;
    }

    private function splitMulti(string $value): array
    {
        if ($value === '') {
            return [];
        }
        return array_filter(array_map('trim', preg_split('/[,;|]/', $value)));
    }

    private function parseBool(string $value): bool
    {
        return in_array(Str::lower($value), ['1', 'true', 'yes', 'y'], true);
    }

    private function summary(): void
    {
        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Products created', $this->productsCreated],
                ['Variants created', $this->variantsCreated],
                ['Ring sizes created', $this->sizesCreated],
                ['Images linked', $this->imagesCreated],
                ['Rows skipped', $this->rowsSkipped],
                ['Warnings', $this->warnings],
            ]
        );

        if ($this->rowsSkipped || $this->warnings) {
            $this->warn("See {$this->logFile} for details.");
        }
    }

    private function log(string $message): void
    {
        $line = '[' . now()->toDateTimeString() . '] ' . $message . PHP_EOL;
        file_put_contents($this->logFile, $line, FILE_APPEND);
        Log::channel('single')->info('[Sheet Import] ' . $message);
    }
}

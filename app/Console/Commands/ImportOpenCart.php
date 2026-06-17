<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Redirect;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportOpenCart extends Command
{
    protected $signature = 'fado:import-opencart
                            {--connection=opencart : DB connection name defined in config/database.php}
                            {--image-base-url= : Base URL to download images from (e.g. https://old-site.com/image/)}
                            {--skip-images : Skip image downloading}
                            {--dry-run : Parse and log without writing to the FADO database}';

    protected $description = 'Import products, categories and images from the legacy OpenCart database';

    private string $logFile;
    private bool $dryRun = false;
    private int $productsImported = 0;
    private int $productsSkipped  = 0;
    private int $imagesDownloaded = 0;
    private int $imagesFailed     = 0;
    private int $categoriesImported = 0;
    private int $redirectsCreated   = 0;

    public function handle(): int
    {
        $this->logFile = storage_path('logs/opencart-import.log');
        $this->dryRun  = (bool) $this->option('dry-run');
        $connection    = $this->option('connection');
        $imageBase     = rtrim((string) $this->option('image-base-url'), '/');
        $skipImages    = (bool) $this->option('skip-images');

        if ($this->dryRun) {
            $this->warn('DRY RUN — nothing will be written to the FADO database.');
        }

        $this->log('=== OpenCart import started ' . now() . ' ===');

        try {
            DB::connection($connection)->getPdo();
        } catch (\Exception $e) {
            $this->error('Cannot connect to OpenCart DB: ' . $e->getMessage());
            $this->log('FATAL: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->importCategories($connection);
        $this->importProducts($connection, $imageBase, $skipImages);

        $this->summary();

        $this->log('=== Import finished ' . now() . ' ===');

        return self::SUCCESS;
    }

    // -------------------------------------------------------------------------
    // Categories
    // -------------------------------------------------------------------------

    private function importCategories(string $connection): void
    {
        $this->info('Importing categories…');

        $rows = DB::connection($connection)
            ->table('oc_category as c')
            ->join('oc_category_description as cd', 'cd.category_id', '=', 'c.category_id')
            ->where('cd.language_id', 1)
            ->where('c.status', 1)
            ->select('c.category_id', 'c.parent_id', 'c.sort_order', 'cd.name')
            ->orderBy('c.parent_id')
            ->orderBy('c.sort_order')
            ->get();

        // First pass — create without parent linkage so FKs resolve
        $ocIdToFadoId = [];

        foreach ($rows as $row) {
            $slug = Str::slug($row->name) ?: 'category-' . $row->category_id;

            if (! $this->dryRun) {
                $category = Category::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name'       => $row->name,
                        'sort_order' => $row->sort_order,
                    ]
                );
                $ocIdToFadoId[$row->category_id] = $category->id;
            } else {
                $ocIdToFadoId[$row->category_id] = null;
                $this->line("  [DRY] Category: {$row->name} ({$slug})");
            }

            $this->categoriesImported++;
        }

        // Second pass — wire parent_id now that all categories exist
        if (! $this->dryRun) {
            foreach ($rows as $row) {
                if ($row->parent_id && isset($ocIdToFadoId[$row->parent_id], $ocIdToFadoId[$row->category_id])) {
                    Category::where('id', $ocIdToFadoId[$row->category_id])
                        ->update(['parent_id' => $ocIdToFadoId[$row->parent_id]]);
                }
            }
        }

        $this->info("  → {$this->categoriesImported} categories processed.");
    }

    // -------------------------------------------------------------------------
    // Products
    // -------------------------------------------------------------------------

    private function importProducts(string $connection, string $imageBase, bool $skipImages): void
    {
        $this->info('Importing products…');

        $sterlingId = Metal::where('slug', 'sterling-silver')->value('id');

        if (! $sterlingId && ! $this->dryRun) {
            $this->error('Sterling Silver metal not found — run db:seed first.');
            return;
        }

        $products = DB::connection($connection)
            ->table('oc_product as p')
            ->join('oc_product_description as pd', function ($j) {
                $j->on('pd.product_id', '=', 'p.product_id')
                  ->where('pd.language_id', 1);
            })
            ->select(
                'p.product_id',
                'p.model',
                'p.price',
                'p.status',
                'p.image',
                'pd.name',
                'pd.description',
                'pd.meta_description as short_description',
            )
            ->get();

        $this->output->progressStart($products->count());

        foreach ($products as $oc) {
            try {
                $this->importProduct($oc, $connection, $sterlingId, $imageBase, $skipImages);
            } catch (\Throwable $e) {
                $this->productsSkipped++;
                $this->log("SKIP product #{$oc->product_id} ({$oc->name}): " . $e->getMessage());
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    private function importProduct(
        object $oc,
        string $connection,
        ?int   $sterlingId,
        string $imageBase,
        bool   $skipImages,
    ): void {
        // Resolve slug — prefer old SEO alias, fall back to name
        $slug = $this->resolveSlug($connection, $oc->product_id, $oc->name);

        if ($this->dryRun) {
            $this->line("  [DRY] Product: {$oc->name} → /{$slug}");
            $this->productsImported++;
            return;
        }

        $product = Product::updateOrCreate(
            ['opencart_id' => $oc->product_id],
            [
                'name'              => $oc->name,
                'slug'              => $slug,
                'description'       => $this->cleanHtml($oc->description ?? ''),
                'short_description' => strip_tags($oc->short_description ?? ''),
                'is_active'         => (bool) $oc->status,
            ]
        );

        // Default variant — Sterling Silver, no gemstone
        ProductVariant::firstOrCreate(
            [
                'product_id'  => $product->id,
                'metal_id'    => $sterlingId,
                'gemstone_id' => null,
            ],
            [
                'sku'       => $oc->model ?: 'OC-' . $oc->product_id,
                'price_eur' => (float) $oc->price,
                'stock'     => 0,
                'is_active' => true,
            ]
        );

        // Categories
        $this->attachCategories($product, $oc->product_id, $connection);

        // Images
        if (! $skipImages) {
            $this->importImages($product, $oc, $connection, $imageBase);
        }

        // SEO redirect
        $this->createRedirect($connection, $oc->product_id, $slug);

        $this->productsImported++;
    }

    private function resolveSlug(string $connection, int $productId, string $name): string
    {
        $alias = DB::connection($connection)
            ->table('oc_url_alias')
            ->where('query', "product_id={$productId}")
            ->orWhere('query', "product/product&product_id={$productId}")
            ->value('keyword');

        $base = $alias ? Str::slug($alias) : Str::slug($name);
        $slug = $base ?: 'product-' . $productId;

        // Ensure uniqueness
        $original = $slug;
        $counter  = 1;
        while (Product::where('slug', $slug)->where('opencart_id', '!=', $productId)->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    private function attachCategories(Product $product, int $ocProductId, string $connection): void
    {
        $ocCategoryIds = DB::connection($connection)
            ->table('oc_product_to_category')
            ->where('product_id', $ocProductId)
            ->pluck('category_id');

        foreach ($ocCategoryIds as $ocCatId) {
            $ocSlug = DB::connection($connection)
                ->table('oc_category_description')
                ->where('category_id', $ocCatId)
                ->where('language_id', 1)
                ->value('name');

            if ($ocSlug) {
                $category = Category::where('slug', Str::slug($ocSlug))->first();
                if ($category) {
                    $product->categories()->syncWithoutDetaching([$category->id]);
                }
            }
        }
    }

    private function importImages(Product $product, object $oc, string $connection, string $imageBase): void
    {
        $allImages = collect();

        if ($oc->image) {
            $allImages->push((object) ['image' => $oc->image, 'sort_order' => 0]);
        }

        $extras = DB::connection($connection)
            ->table('oc_product_image')
            ->where('product_id', $oc->product_id)
            ->orderBy('sort_order')
            ->get(['image', 'sort_order']);

        $allImages = $allImages->merge($extras)->unique('image');

        $isPrimary = true;

        foreach ($allImages as $img) {
            $localPath = $this->downloadImage($imageBase, $img->image, $product->id);

            if (! $localPath) {
                $isPrimary = false;
                continue;
            }

            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'path' => $localPath],
                ['sort_order' => $img->sort_order, 'is_primary' => $isPrimary]
            );

            $isPrimary = false;
        }
    }

    private function downloadImage(string $imageBase, string $ocPath, int $productId): ?string
    {
        if (! $imageBase) {
            $this->imagesFailed++;
            $this->log("No image base URL — skipping image: {$ocPath}");
            return null;
        }

        $url       = $imageBase . '/' . ltrim($ocPath, '/');
        $ext       = pathinfo($ocPath, PATHINFO_EXTENSION) ?: 'jpg';
        $filename  = 'products/' . $productId . '/' . Str::uuid() . '.' . $ext;

        try {
            $response = Http::timeout(15)->get($url);

            if (! $response->successful()) {
                throw new \RuntimeException("HTTP {$response->status()}");
            }

            Storage::disk('public')->put($filename, $response->body());
            $this->imagesDownloaded++;

            return $filename;
        } catch (\Throwable $e) {
            $this->imagesFailed++;
            $this->log("Image download failed ({$url}): " . $e->getMessage());
            return null;
        }
    }

    private function createRedirect(string $connection, int $productId, string $newSlug): void
    {
        $oldPaths = [
            "/index.php?route=product/product&product_id={$productId}",
        ];

        $alias = DB::connection($connection)
            ->table('oc_url_alias')
            ->where('query', "product_id={$productId}")
            ->orWhere('query', "product/product&product_id={$productId}")
            ->value('keyword');

        if ($alias) {
            $oldPaths[] = '/' . ltrim($alias, '/');
        }

        foreach ($oldPaths as $from) {
            if (! Redirect::where('from_path', $from)->exists()) {
                Redirect::create([
                    'from_path'   => $from,
                    'to_path'     => '/products/' . $newSlug,
                    'status_code' => 301,
                ]);
                $this->redirectsCreated++;
            }
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function cleanHtml(string $html): string
    {
        // Strip <script> and <style> blocks, preserve other HTML
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);

        return trim($html);
    }

    private function summary(): void
    {
        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Categories imported',  $this->categoriesImported],
                ['Products imported',     $this->productsImported],
                ['Products skipped',      $this->productsSkipped],
                ['Images downloaded',     $this->imagesDownloaded],
                ['Images failed',         $this->imagesFailed],
                ['Redirects created',     $this->redirectsCreated],
            ]
        );

        if ($this->productsSkipped || $this->imagesFailed) {
            $this->warn("See {$this->logFile} for details on skipped items.");
        }
    }

    private function log(string $message): void
    {
        $line = '[' . now()->toDateTimeString() . '] ' . $message . PHP_EOL;
        file_put_contents($this->logFile, $line, FILE_APPEND);
        Log::channel('single')->info('[OpenCart Import] ' . $message);
    }
}

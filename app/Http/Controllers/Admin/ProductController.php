<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Gemstone;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Traits\OptimizesImages;
use Database\Seeders\RingSizeSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    use OptimizesImages;

    public function index(Request $request): View
    {
        $query = Product::with(['primaryImage', 'variants', 'categories'])
            ->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $products = $query->paginate(20)->withQueryString();

        return view('general.products.list', compact('products'));
    }

    public function create(): View
    {
        [$metals, $gemstones, $categories, $collections, $ringSizes] = $this->formData();

        return view('general.products.create', compact(
            'metals', 'gemstones', 'categories', 'collections', 'ringSizes'
        ));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $slug = $this->uniqueSlug(
            $request->filled('slug') ? $request->input('slug') : Str::slug($request->input('name'))
        );

        $product = Product::create([
            'name'              => $request->input('name'),
            'slug'              => $slug,
            'description'       => $request->input('description', ''),
            'short_description' => $request->input('short_description', ''),
            'is_active'         => $request->boolean('is_active'),
            'is_bestseller'     => $request->boolean('is_bestseller'),
        ]);

        $product->categories()->sync($request->input('category_ids', []));
        $product->collections()->sync($request->input('collection_ids', []));

        $this->syncVariants($product, $request->input('variants', []));
        $this->syncSizes($product, $request->input('sizes', []));
        $this->uploadImages($product, $request->file('images', []), $request->input('image_quality'));

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" created.");
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'allVariants.metal', 'allVariants.gemstone', 'categories', 'collections', 'sizes']);

        [$metals, $gemstones, $categories, $collections, $ringSizes] = $this->formData();

        return view('general.products.edit', compact(
            'product', 'metals', 'gemstones', 'categories', 'collections', 'ringSizes'
        ));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $inputSlug = $request->filled('slug') ? $request->input('slug') : Str::slug($request->input('name'));
        $slug = ($inputSlug === $product->slug)
            ? $product->slug
            : $this->uniqueSlug($inputSlug, $product->id);

        $product->update([
            'name'              => $request->input('name'),
            'slug'              => $slug,
            'description'       => $request->input('description', ''),
            'short_description' => $request->input('short_description', ''),
            'is_active'         => $request->boolean('is_active'),
            'is_bestseller'     => $request->boolean('is_bestseller'),
        ]);

        $product->categories()->sync($request->input('category_ids', []));
        $product->collections()->sync($request->input('collection_ids', []));

        foreach ($request->input('delete_images', []) as $imageId) {
            $image = ProductImage::where('id', $imageId)->where('product_id', $product->id)->first();
            if ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        $this->syncVariants($product, $request->input('variants', []));
        $this->syncSizes($product, $request->input('sizes', []));
        $this->uploadImages($product, $request->file('images', []), $request->input('image_quality'));

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Product \"{$product->name}\" updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    // -------------------------------------------------------------------------

    private function formData(): array
    {
        return [
            Metal::orderBy('name')->get(),
            Gemstone::orderBy('name')->get(),
            Category::whereNull('parent_id')->with('children')->orderBy('sort_order')->get(),
            Collection::orderBy('name')->get(),
            RingSizeSeeder::SIZES,
        ];
    }

    private function syncVariants(Product $product, array $variantsData): void
    {
        $submittedIds = [];

        foreach ($variantsData as $data) {
            if (empty($data['metal_id'])) {
                continue;
            }

            $attributes = [
                'product_id'  => $product->id,
                'metal_id'    => $data['metal_id'],
                'gemstone_id' => ($data['gemstone_id'] ?? '') ?: null,
            ];
            $values = [
                'sku'             => $data['sku'] ?? null,
                'price_eur'       => (float) ($data['price_eur'] ?? 0),
                'sale_price_eur'  => ($data['sale_price_eur'] ?? '') !== '' ? (float) $data['sale_price_eur'] : null,
                'second_metal_id' => ($data['second_metal_id'] ?? '') ?: null,
                'colour'          => ($data['colour'] ?? '') ?: null,
                'stock'           => (int) ($data['stock'] ?? 0),
                'is_active'       => ! empty($data['is_active']),
            ];

            if (! empty($data['id'])) {
                $variant = ProductVariant::where('id', $data['id'])
                    ->where('product_id', $product->id)
                    ->first();

                if ($variant) {
                    $variant->update(array_merge($attributes, $values));
                    $submittedIds[] = $variant->id;
                    continue;
                }
            }

            $variant = ProductVariant::create(array_merge($attributes, $values));
            $submittedIds[] = $variant->id;
        }

        ProductVariant::where('product_id', $product->id)
            ->when(! empty($submittedIds), fn ($q) => $q->whereNotIn('id', $submittedIds))
            ->delete();
    }

    private function syncSizes(Product $product, array $sizesData): void
    {
        $submittedSizes = [];

        foreach ($sizesData as $usSize => $data) {
            if (empty($data['enabled'])) {
                continue;
            }
            ProductSize::updateOrCreate(
                ['product_id' => $product->id, 'us_size' => $usSize],
                ['stock' => (int) ($data['stock'] ?? 0)]
            );
            $submittedSizes[] = (string) $usSize;
        }

        ProductSize::where('product_id', $product->id)
            ->when(! empty($submittedSizes), fn ($q) => $q->whereNotIn('us_size', $submittedSizes))
            ->delete();
    }

    private function uploadImages(Product $product, array $files, ?string $qualityLevel = null): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $sortOrder  = $product->images()->max('sort_order') ?? 0;

        foreach ($files as $file) {
            $path = $this->storeImageWithQuality($file, "products/{$product->id}", $qualityLevel, 1600, 82);
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'sort_order' => ++$sortOrder,
                'is_primary' => ! $hasPrimary,
            ]);
            $hasPrimary = true;
        }
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug     = $base ?: 'product';
        $original = $slug;
        $counter  = 1;

        while (
            Product::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}

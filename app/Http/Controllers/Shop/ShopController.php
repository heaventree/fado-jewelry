<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Consultation;
use App\Models\Gemstone;
use App\Models\Metal;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function home(): View
    {
        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')->get();
        $featuredCollections = Collection::limit((int) Setting::get('featured_collections_count', 6))->get();
        $newArrivals = Product::with(['variants.metal', 'primaryImage', 'images'])
            ->where('is_active', true)
            ->latest()
            ->limit((int) Setting::get('new_arrivals_count', 8))
            ->get();
        $bestSellers = Product::with(['variants.metal', 'primaryImage', 'images'])
            ->where('is_active', true)
            ->where('is_bestseller', true)
            ->latest()
            ->limit((int) Setting::get('new_arrivals_count', 8))
            ->get();
        $onSale = Product::with(['variants.metal', 'primaryImage', 'images'])
            ->where('is_active', true)
            ->whereHas('variants', fn ($q) => $q->whereNotNull('sale_price_eur')->whereColumn('sale_price_eur', '<', 'price_eur'))
            ->latest()
            ->limit((int) Setting::get('new_arrivals_count', 8))
            ->get();

        $featuredProductId = Setting::get('featured_product_id');
        $featuredProduct = $featuredProductId
            ? Product::with(['variants.metal', 'images'])->where('is_active', true)->find($featuredProductId)
            : null;

        return view('shop.home', compact('topCategories', 'featuredCollections', 'newArrivals', 'bestSellers', 'onSale', 'featuredProduct'));
    }

    public function jewellery(Request $request): View
    {
        [$products, $metals, $gemstones, $allCollections, $colours, $filters] = $this->buildProductQuery($request);

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('shop.listing', [
            'pageTitle'        => 'All Jewellery',
            'pageSubtitle'     => 'Fine Irish jewellery — handcrafted in sterling silver, gold and platinum.',
            'bannerImage'      => null,
            'products'         => $products,
            'metals'           => $metals,
            'gemstones'        => $gemstones,
            'allCollections'   => $allCollections,
            'colours'          => $colours,
            'filters'          => $filters,
            'topCategories'    => $topCategories,
            'activeCategory'   => null,
            'activeCollection' => null,
            'breadcrumbs'      => [['label' => 'Jewellery']],
        ]);
    }

    public function category(Request $request, string $category): View
    {
        $cat = Category::where('slug', $category)->firstOrFail();

        // Include products in this category AND all child categories
        $categoryIds = $cat->children->pluck('id')->prepend($cat->id);

        [$products, $metals, $gemstones, $allCollections, $colours, $filters] = $this->buildProductQuery($request, categoryIds: $categoryIds);

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')->get();

        $breadcrumbs = [['label' => 'Jewellery', 'url' => route('shop.jewellery')]];
        if ($cat->parent) {
            $breadcrumbs[] = ['label' => $cat->parent->name, 'url' => route('shop.category', $cat->parent->slug)];
        }
        $breadcrumbs[] = ['label' => $cat->name];

        return view('shop.listing', [
            'pageTitle'        => $cat->banner_title    ?? $cat->name,
            'pageSubtitle'     => $cat->banner_description ?? 'Fine Irish ' . strtolower($cat->name) . ' — handcrafted in sterling silver, gold and platinum.',
            'bannerImage'      => $cat->banner_image,
            'products'         => $products,
            'metals'           => $metals,
            'gemstones'        => $gemstones,
            'allCollections'   => $allCollections,
            'colours'          => $colours,
            'filters'          => $filters,
            'topCategories'    => $topCategories,
            'activeCategory'   => $cat,
            'activeCollection' => null,
            'breadcrumbs'      => $breadcrumbs,
        ]);
    }

    public function collections(): View
    {
        $collections = Collection::withCount('products')->orderBy('name')->get();
        return view('shop.collections', compact('collections'));
    }

    public function collection(Request $request, string $slug): View
    {
        $col = Collection::where('slug', $slug)->firstOrFail();

        [$products, $metals, $gemstones, $allCollections, $colours, $filters] = $this->buildProductQuery($request, collectionSlug: $slug);

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('shop.listing', [
            'pageTitle'        => $col->banner_title    ?? $col->name,
            'pageSubtitle'     => $col->banner_description ?? 'The ' . $col->name . ' — a celebration of Irish craft and heritage.',
            'bannerImage'      => $col->banner_image,
            'products'         => $products,
            'metals'           => $metals,
            'gemstones'        => $gemstones,
            'allCollections'   => $allCollections,
            'colours'          => $colours,
            'filters'          => $filters,
            'topCategories'    => $topCategories,
            'activeCategory'   => null,
            'activeCollection' => $col,
            'breadcrumbs'      => [
                ['label' => 'Collections', 'url' => route('shop.collections')],
                ['label' => $col->name],
            ],
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'images',
            'variants.metal',
            'variants.secondMetal',
            'variants.gemstone',
            'sizes',
            'categories.parent',
            'collections',
        ]);

        $activeVariants = $product->variants; // already scoped to is_active=true

        // Flatten variant data for the JS switcher
        $variantData = $activeVariants->map(fn ($v) => [
            'id'               => $v->id,
            'metal_id'         => $v->metal_id,
            'metal_name'       => $v->metal?->name,
            'gemstone_id'      => $v->gemstone_id,
            'gemstone_name'    => $v->gemstone?->name,
            'second_metal_id'  => $v->second_metal_id,
            'second_metal_name'=> $v->secondMetal?->name,
            'colour'           => $v->colour,
            'price_eur'        => (float) $v->price_eur,
            'sale_price_eur'   => $v->sale_price_eur !== null ? (float) $v->sale_price_eur : null,
            'stock'            => $v->stock,
            'sku'              => $v->sku,
        ])->values();

        $defaultVariant = $activeVariants->first();

        // Unique metals for the dropdown
        $metals = $activeVariants
            ->sortBy('metal.name')
            ->unique('metal_id')
            ->map(fn ($v) => $v->metal)
            ->filter()
            ->values();

        // Gemstones available for the default metal (shown on first load)
        $defaultMetalId = $defaultVariant?->metal_id;
        $gemstones = $activeVariants
            ->where('metal_id', $defaultMetalId)
            ->whereNotNull('gemstone_id')
            ->unique('gemstone_id')
            ->map(fn ($v) => $v->gemstone)
            ->filter()
            ->values();

        // Image list for JS thumbnail switching
        $imageData = $product->images->map(fn ($img) => [
            'id'  => $img->id,
            'url' => Storage::url($img->path),
        ])->values();

        // Related products from the same categories
        $categoryIds = $product->categories->pluck('id');
        $related = Product::with(['primaryImage', 'variants.metal'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($categoryIds->isNotEmpty(), fn ($q) =>
                $q->whereHas('categories', fn ($cq) => $cq->whereIn('categories.id', $categoryIds))
            )
            ->limit((int) Setting::get('related_products_count', 4))
            ->get();

        return view('shop.product', compact(
            'product', 'variantData', 'defaultVariant',
            'metals', 'gemstones', 'imageData', 'related'
        ));
    }


    public function wishlist(): View
    {
        return view('shop.coming-soon', ['page' => 'Wishlist']);
    }

    public function about(): View
    {
        return view('shop.about');
    }

    public function contact(): View
    {
        return view('shop.contact');
    }

    public function contactStore(Request $request): RedirectResponse
    {
        if (! Setting::get('consultation_enabled', '1')) {
            return back()->with('error', 'The consultation form is currently unavailable. Please contact us by phone or email.');
        }

        $data = $request->validate([
            'name'              => ['required', 'string', 'max:120'],
            'email'             => ['required', 'email', 'max:200'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'message'           => ['required', 'string', 'max:2000'],
            'preferred_contact' => ['required', 'in:email,phone'],
        ]);

        Consultation::create($data);

        return back()->with('consultation_sent', true);
    }

    public function privacy(): View
    {
        return view('shop.coming-soon', ['page' => 'Privacy Policy']);
    }

    public function search(Request $request): View
    {
        $query  = trim($request->string('q')->toString());
        $perPage = (int) Setting::get('products_per_page', 16);

        $products = collect();
        $total    = 0;

        if (strlen($query) >= 2) {
            $builder = Product::query()
                ->where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%')
                      ->orWhere('short_description', 'like', '%' . $query . '%');
                })
                ->with(['primaryImage', 'variants.metal', 'variants.gemstone'])
                ->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", ['%' . $query . '%'])
                ->latest();

            $products = $builder->paginate($perPage)->withQueryString();
            $total    = $products->total();
        }

        return view('shop.search', compact('query', 'products', 'total'));
    }

    public function newsletterSubscribe(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        return back()->with('newsletter_success', 'Thank you for subscribing!');
    }

    public function switchCurrency(Request $request): RedirectResponse
    {
        $request->validate(['currency' => ['required', 'string', 'size:3']]);
        app(\App\Services\CurrencyService::class)->setForSession($request->input('currency'));
        return back();
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function buildProductQuery(
        Request $request,
        ?\Illuminate\Support\Collection $categoryIds = null,
        ?string $collectionSlug = null
    ): array {
        $metals         = Metal::orderBy('name')->get();
        $gemstones      = Gemstone::orderBy('name')->get();
        $allCollections = Collection::orderBy('name')->get();
        $colours        = \App\Models\ProductVariant::query()
                            ->whereNotNull('colour')
                            ->distinct()
                            ->orderBy('colour')
                            ->pluck('colour');

        // Parse filters from request
        $filters = [
            'metals'        => $request->array('metals'),
            'gemstones'     => $request->array('gemstones'),
            'collections'   => $request->array('collections'),
            'second_metals' => $request->array('second_metals'),
            'colours'       => $request->array('colours'),
            'price_min'     => $request->integer('price_min', 0),
            'price_max'     => $request->integer('price_max', 0),
            'sort'          => $request->string('sort', 'newest')->toString(),
            'search'        => $request->string('search')->toString(),
        ];

        $query = Product::query()
            ->where('is_active', true)
            ->with(['primaryImage', 'variants.metal', 'variants.secondMetal', 'variants.gemstone']);

        // Scope by category
        if ($categoryIds) {
            $query->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds));
        }

        // Scope by collection (URL segment takes precedence over checkbox filter)
        if ($collectionSlug) {
            $query->whereHas('collections', fn ($q) => $q->where('slug', $collectionSlug));
        } elseif (!empty($filters['collections'])) {
            $query->whereHas('collections', fn ($q) => $q->whereIn('slug', $filters['collections']));
        }

        // Text search
        if ($filters['search']) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // Metal filter — product must have at least one matching active variant
        if (!empty($filters['metals'])) {
            $query->whereHas('variants', fn ($q) => $q
                ->where('is_active', true)
                ->whereHas('metal', fn ($m) => $m->whereIn('slug', $filters['metals']))
            );
        }

        // Second metal / finish filter
        if (!empty($filters['second_metals'])) {
            $query->whereHas('variants', fn ($q) => $q
                ->where('is_active', true)
                ->whereHas('secondMetal', fn ($m) => $m->whereIn('slug', $filters['second_metals']))
            );
        }

        // Gemstone filter
        if (!empty($filters['gemstones'])) {
            $query->whereHas('variants', fn ($q) => $q
                ->where('is_active', true)
                ->whereHas('gemstone', fn ($g) => $g->whereIn('slug', $filters['gemstones']))
            );
        }

        // Colour filter
        if (!empty($filters['colours'])) {
            $query->whereHas('variants', fn ($q) => $q
                ->where('is_active', true)
                ->whereIn('colour', $filters['colours'])
            );
        }

        // Price range — product's min variant price must fall within range
        if ($filters['price_min'] > 0) {
            $query->whereHas('variants', fn ($q) => $q
                ->where('is_active', true)
                ->where('price_eur', '>=', $filters['price_min'])
            );
        }
        if ($filters['price_max'] > 0) {
            $query->whereHas('variants', fn ($q) => $q
                ->where('is_active', true)
                ->where('price_eur', '<=', $filters['price_max'])
            );
        }

        // Sort
        match ($filters['sort']) {
            'price_asc'  => $query->orderByRaw('(SELECT MIN(price_eur) FROM product_variants WHERE product_variants.product_id = products.id AND is_active = 1) ASC'),
            'price_desc' => $query->orderByRaw('(SELECT MIN(price_eur) FROM product_variants WHERE product_variants.product_id = products.id AND is_active = 1) DESC'),
            'name_asc'   => $query->orderBy('name'),
            default      => $query->latest(),
        };

        $products = $query->paginate((int) Setting::get('products_per_page', 16))->withQueryString();

        return [$products, $metals, $gemstones, $allCollections, $colours, $filters];
    }
}

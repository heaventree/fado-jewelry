<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Consultation;
use App\Models\Gemstone;
use App\Models\Metal;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        if ($request->ajax()) {
            return view('shop.partials.listing-results', compact('products', 'filters'));
        }

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->get();

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

        if ($request->ajax()) {
            return view('shop.partials.listing-results', compact('products', 'filters'));
        }

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->get();

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

        if ($request->ajax()) {
            return view('shop.partials.listing-results', compact('products', 'filters'));
        }

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->get();

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

        // "People viewing this" — real, lightweight, TTL-based unique-session counter.
        // No Redis/websockets: a small [session_id => last_seen_timestamp] map per
        // product, stored in the default cache store (database/file — whatever's
        // configured), pruned to the last 10 minutes on every view. Hidden below 2
        // viewers, since "1 viewer" is just the current visitor and isn't real social
        // proof (it would always show "1" for every solo visitor, which is misleading).
        $viewWindowSeconds = 600;
        $viewersKey = "product_viewers:{$product->id}";
        $viewers = Cache::get($viewersKey, []);
        $now = now()->timestamp;
        $viewers = array_filter($viewers, fn ($ts) => ($now - $ts) < $viewWindowSeconds);
        $viewers[session()->getId()] = $now;
        Cache::put($viewersKey, $viewers, $viewWindowSeconds);
        $viewingCount = count($viewers) >= 2 ? count($viewers) : 0;

        // "People bought this recently" — real completed orders (OrderItem rows) in
        // the last 48 hours, not a fabricated/random number. There's no persisted
        // "added to cart" event log (CartService is session-only and never writes
        // history), so this counts actual distinct orders containing this product —
        // a stronger, equally real signal. Hidden when zero.
        $recentBuyersCount = OrderItem::where('product_id', $product->id)
            ->where('created_at', '>=', now()->subHours(48))
            ->distinct('order_id')
            ->count('order_id');

        return view('shop.product', compact(
            'product', 'variantData', 'defaultVariant',
            'metals', 'gemstones', 'imageData', 'related',
            'viewingCount', 'recentBuyersCount'
        ));
    }


    public function wishlist(): View
    {
        return view('shop.coming-soon', ['page' => 'Wishlist']);
    }

    public function faq(): View
    {
        return view('shop.faq');
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

    public function terms(): View
    {
        return view('shop.terms');
    }

    /**
     * Reuses the same shop.listing view + shop.partials.listing-results AJAX partial as
     * jewellery/category/collection — no dedicated search results template, filtered by
     * the "search" param instead of a category/collection scope. buildProductQuery()'s
     * existing text search (name LIKE) handles the query; results are overridden to an
     * empty paginator below 2 characters so a blank/1-char query doesn't show the full
     * catalogue (matches the previous dedicated search() behaviour).
     */
    public function search(Request $request): View
    {
        $query = trim($request->string('q')->toString());
        // Mutate the actual GET parameter bag (not merge(), which request->query()/
        // withQueryString() don't see) so pagination/sort links built from
        // withQueryString() correctly carry the search term forward.
        $request->query->set('search', $query);

        [$products, $metals, $gemstones, $allCollections, $colours, $filters] = $this->buildProductQuery($request);

        if (strlen($query) < 2) {
            $products = new \Illuminate\Pagination\LengthAwarePaginator(collect(), 0, (int) Setting::get('products_per_page', 16));
        }

        if ($request->ajax()) {
            return view('shop.partials.listing-results', compact('products', 'filters'));
        }

        $topCategories = Category::whereNull('parent_id')->orderBy('sort_order')
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->get();

        return view('shop.listing', [
            'pageTitle'        => $query !== '' ? 'Search results for "' . $query . '"' : 'Search',
            'pageSubtitle'     => $products->total() . ' ' . \Illuminate\Support\Str::plural('result', $products->total()) . ' found',
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
            'breadcrumbs'      => [['label' => 'Search']],
        ]);
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

<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- ── Static pages ─────────────────────────────────────────────────────── --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('shop.jewellery') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('shop.collections') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('shop.about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('shop.contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('shop.faq') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('shop.terms') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('shop.privacy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- ── Categories ───────────────────────────────────────────────────────── --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('shop.category', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- ── Collections ──────────────────────────────────────────────────────── --}}
    @foreach($collections as $collection)
    <url>
        <loc>{{ route('shop.collection', $collection->slug) }}</loc>
        <lastmod>{{ $collection->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- ── Products ─────────────────────────────────────────────────────────── --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('shop.product', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- ── Blog Posts ───────────────────────────────────────────────────────── --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

</urlset>

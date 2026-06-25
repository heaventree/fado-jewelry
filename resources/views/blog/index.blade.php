@extends('shop.layouts.app')

@section('title', 'Blog — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Blog</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><span class="h6 text-main">Blog</span></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">
        @if($posts->isEmpty())
            <div class="text-center py-5">
                <p class="h5 text-muted">No posts yet. Check back soon.</p>
            </div>
        @else
            <div class="row">
                @foreach($posts as $post)
                <div class="col-lg-4 col-sm-6 mb-4">
                    <div class="article-blog type-space-2 hover-img4">
                        <a href="{{ route('blog.show', $post) }}" class="entry_image img-style4">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" data-src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="lazyload aspect-ratio-0">
                            @else
                                <img src="/images/ochaka/blog/blog-11.jpg" alt="{{ $post->title }}" class="aspect-ratio-0">
                            @endif
                        </a>
                        <div class="entry_tag">
                            <span class="name-tag h6">{{ $post->published_at->format('d M Y') }}</span>
                        </div>
                        <div class="blog-content">
                            <a href="{{ route('blog.show', $post) }}" class="entry_name link h4">
                                {{ $post->title }}
                            </a>
                            @if($post->excerpt)
                            <p class="text h6">{{ Str::limit($post->excerpt, 120) }}</p>
                            @endif
                            <a href="{{ route('blog.show', $post) }}" class="tf-btn-line">
                                Read more
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="tf-pagination-wrap">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>

@endsection

@extends('shop.layouts.app')

@section('title', $post->title . ' — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))

@section('content')

<section class="s-page-title">
    <div class="container">
        <div class="content">
            <h1 class="title-page">Blog</h1>
            <ul class="breadcrumbs-page">
                <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><a href="{{ route('blog.index') }}" class="h6 link">Blog</a></li>
                <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                <li><span class="h6 text-main">{{ Str::limit($post->title, 40) }}</span></li>
            </ul>
        </div>
    </div>
</section>

<section class="flat-spacing">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($post->featured_image)
                <div class="mb-4">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-100 rounded">
                </div>
                @endif

                <h1 class="mb-3">{{ $post->title }}</h1>

                <div class="mb-4 text-muted h6">
                    @if($post->author)
                        <span>By {{ $post->author }}</span>
                        <span class="mx-2">|</span>
                    @endif
                    @if($post->published_at)
                        <span>{{ $post->published_at->format('d F Y') }}</span>
                    @endif
                </div>

                <div class="blog-body mb-5">
                    {!! nl2br(e($post->body)) !!}
                </div>

                <a href="{{ route('blog.index') }}" class="tf-btn-line">
                    <i class="icon icon-caret-left"></i> Back to Blog
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

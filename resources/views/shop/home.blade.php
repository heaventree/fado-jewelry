@extends('shop.layouts.app')

@section('title', 'FADÓ Jewellery')

@section('content')

<div class="container py-5 text-center">
    <h1 style="font-family: Georgia, serif; color: var(--fado-deep-green); font-size: 2.5rem; letter-spacing: .08em;">
        FADÓ Jewellery
    </h1>
    <p class="text-muted mt-2" style="letter-spacing: .15em; font-size: .875rem; text-transform: uppercase;">
        Fine Irish Jewellery — Homepage coming in Phase 3 Step 2
    </p>
    <div class="mt-4 d-flex gap-3 justify-content-center">
        <a href="{{ route('shop.jewellery') }}" class="btn-fado-primary" style="display:inline-block; border-radius:2px; padding:12px 28px; text-decoration:none">
            Shop Jewellery
        </a>
        <a href="{{ route('shop.collections') }}" class="btn-fado-outline" style="display:inline-block; border-radius:2px; padding:11px 28px; text-decoration:none">
            View Collections
        </a>
    </div>
</div>

@endsection

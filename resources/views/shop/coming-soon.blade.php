@extends('shop.layouts.app')

@section('title', $page . ' — FADÓ Jewellery')

@section('content')
<div class="container py-5 text-center">
    <p style="font-size:.7rem; letter-spacing:.25em; text-transform:uppercase; color: var(--fado-gold); margin-bottom:12px">Coming Soon</p>
    <h1 style="font-family: Georgia, serif; color: var(--fado-deep-green); font-size: 2rem;">{{ $page }}</h1>
    <p class="text-muted mt-2" style="font-size:.875rem">
        This page is being built in Phase 3.
    </p>
    <a href="{{ route('shop.home') }}" class="btn-fado-outline mt-3" style="display:inline-block; border-radius:2px; padding:10px 24px; text-decoration:none">
        ← Back to Home
    </a>
</div>
@endsection

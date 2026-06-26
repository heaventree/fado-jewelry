@extends('shop.layouts.app')

@section('title', \App\Models\Setting::get('faq_meta_title') ?: 'FAQ — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', \App\Models\Setting::get('faq_meta_description') ?: '')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">Frequently Asked Questions</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">Frequently Asked Questions</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- FAQ -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <ul class="faq-list">
                            @if($faqs->isNotEmpty())
                            <li class="faq-item">
                                <div class="faq_wrap" id="faq-all">
                                    @foreach($faqs as $faq)
                                    <div class="accordion-faq accor-mn-pl">
                                        <div class="accordion-title {{ $loop->first ? '' : 'collapsed' }}" data-bs-target="#faq-{{ $faq->id }}" role="button" data-bs-toggle="collapse"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faq-{{ $faq->id }}">
                                            <span class="text h5">{{ $loop->iteration }}. {{ $faq->question }}</span>
                                            <span class="icon"><span class="ic-accordion-custom"></span></span>
                                        </div>
                                        <div id="faq-{{ $faq->id }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#faq-all">
                                            <div class="accordion-body">
                                                <p class="h6">{{ $faq->answer }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </li>
                            @else
                            <li class="faq-item">
                                <p class="h6 text-muted text-center py-4">No FAQs available yet.</p>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-3 d-none d-lg-block">
                        <div class="blog-sidebar sidebar-content-wrap sticky-top">
                            <div class="sidebar-item">
                                <h4 class="sb-title">Frequently asked questions</h4>
                            </div>
                            <div class="sidebar-item">
                                <div class="sb-banner hover-img">
                                    <a href="{{ route('shop.jewellery') }}" class="image img-style">
                                        @php $faqBanner = \App\Models\Setting::get('faq_banner_image'); @endphp
                                        @if($faqBanner)
                                            <img src="{{ asset('storage/' . $faqBanner) }}" data-src="{{ asset('storage/' . $faqBanner) }}" alt="Banner">
                                        @else
                                            <img src="/images/ochaka/blog/side-banner.jpg" data-src="/images/ochaka/blog/side-banner.jpg" alt="Banner">
                                        @endif
                                    </a>
                                    <div class="content">
                                        <h5 class="sub-title text-primary">Browse our range</h5>
                                        <h2 class="fw-semibold title">
                                            <a href="{{ route('shop.jewellery') }}" class="text-white link">
                                                Fine Irish Jewellery
                                            </a>
                                        </h2>
                                        <a href="{{ route('shop.jewellery') }}" class="tf-btn btn-white animate-btn animate-dark">
                                            Shop now
                                            <i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /FAQ -->
@endsection

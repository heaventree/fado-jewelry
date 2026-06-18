@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'About Us — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', 'Learn about FADÓ Jewellery — fine Irish jewellery handcrafted in the Irish tradition. Our story, values and collections.')
@section('canonical', route('shop.about'))

@section('content')

{{-- ── Hero banner ──────────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-deep-green); padding:80px 0 72px; text-align:center; position:relative; overflow:hidden">
    {{-- Subtle decorative ring --}}
    <div style="position:absolute; top:-60px; right:-60px; width:320px; height:320px;
                border:1px solid rgba(255,255,255,.07); border-radius:50%; pointer-events:none"></div>
    <div style="position:absolute; bottom:-80px; left:-80px; width:400px; height:400px;
                border:1px solid rgba(255,255,255,.05); border-radius:50%; pointer-events:none"></div>
    <div class="container" style="position:relative">
        <p style="font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase;
                  color:var(--fado-gold); margin-bottom:16px">Our Story</p>
        <h1 style="font-family:Georgia,serif; font-size:clamp(2rem,4vw,3rem); color:#fff;
                   font-weight:400; margin-bottom:20px; line-height:1.2">
            Fine Irish Jewellery,<br>Crafted with Heart
        </h1>
        <p style="color:rgba(255,255,255,.7); font-size:1.0625rem; max-width:560px; margin:0 auto;
                  line-height:1.8">
            {{ Setting::get('store_name', 'FADÓ Jewellery') }} — Irish for "long ago" —
            creates jewellery that carries the spirit of Ireland wherever you go.
        </p>
    </div>
</div>

{{-- ── Story section — image left, text right ─────────────────────────────── --}}
<div style="background:#fff; padding:80px 0">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div style="aspect-ratio:4/5; background:var(--fado-cream); border-radius:4px; overflow:hidden;
                            position:relative">
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
                                flex-direction:column; gap:12px; color:var(--fado-warm-grey)">
                        <i class="icon icon-image" style="font-size:3rem; opacity:.4"></i>
                        <p style="font-size:.8125rem; opacity:.5; margin:0">Atelier image</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <p style="font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase;
                          color:var(--fado-gold); margin-bottom:16px">Who We Are</p>
                <h2 style="font-family:Georgia,serif; font-size:clamp(1.5rem,3vw,2rem); color:var(--fado-deep-green);
                           font-weight:400; margin-bottom:24px; line-height:1.3">
                    Rooted in Irish Tradition,<br>Made for Today
                </h2>
                <p style="font-size:1rem; color:#555; line-height:1.85; margin-bottom:20px">
                    FADÓ Jewellery was born from a love of Ireland's rich cultural heritage. Every piece in our
                    collection draws on centuries of Irish craft — from the ancient Claddagh tradition to the
                    geometric intricacies of High Cross stonework and the wildflower beauty of the Irish countryside.
                </p>
                <p style="font-size:1rem; color:#555; line-height:1.85; margin-bottom:20px">
                    Each piece is individually crafted in sterling silver, gold, and platinum, then hallmarked
                    by the Assay Office of Ireland — your guarantee of authentic Irish quality.
                </p>
                <p style="font-size:1rem; color:#555; line-height:1.85; margin-bottom:32px">
                    Whether you are celebrating an anniversary, searching for the perfect gift, or simply
                    treating yourself to something beautiful, our jewellery is made to be worn, treasured,
                    and passed down.
                </p>
                <a href="{{ route('shop.jewellery') }}"
                   style="display:inline-block; padding:14px 32px; background:var(--fado-deep-green);
                          color:#fff; text-decoration:none; border-radius:2px; font-size:.9375rem;
                          font-weight:600; letter-spacing:.03em; transition:background .2s"
                   onmouseover="this.style.background='var(--fado-green-mid)'"
                   onmouseout="this.style.background='var(--fado-deep-green)'">
                    Explore Our Collections
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Values strip ─────────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-cream); padding:64px 0">
    <div class="container">
        <div class="row g-4 text-center">
            @foreach([
                ['icon' => 'icon-medal',         'title' => 'Hallmark Certified',   'body' => 'Every piece is assayed and hallmarked by the Assay Office of Ireland, guaranteeing authentic Irish gold and silver.'],
                ['icon' => 'icon-hand-heart',     'title' => 'Handcrafted Quality',  'body' => 'Made by skilled craftspeople using time-honoured techniques. Each piece is finished by hand and inspected before it leaves our studio.'],
                ['icon' => 'icon-leaf',           'title' => 'Irish Heritage',       'body' => 'Our designs draw directly from Irish mythology, landscape, and tradition — the Claddagh, Trinity Knot, High Cross, and more.'],
                ['icon' => 'icon-gift',           'title' => 'Gift Ready',           'body' => 'Every order arrives in our signature FADÓ gift box — deep green with gold foil — ready to give as it arrives.'],
            ] as $value)
            <div class="col-sm-6 col-lg-3">
                <div style="padding:32px 24px; background:#fff; border-radius:4px; height:100%;
                            border:1px solid rgba(188,179,171,.3); transition:box-shadow .2s"
                     onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.06)'"
                     onmouseout="this.style.boxShadow='none'">
                    <div style="width:52px; height:52px; background:var(--fado-cream); border-radius:50%;
                                display:flex; align-items:center; justify-content:center; margin:0 auto 16px">
                        <i class="icon {{ $value['icon'] }}" style="font-size:1.375rem; color:var(--fado-deep-green)"></i>
                    </div>
                    <h3 style="font-family:Georgia,serif; font-size:1.0625rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:10px">
                        {{ $value['title'] }}
                    </h3>
                    <p style="font-size:.875rem; color:#666; line-height:1.75; margin:0">
                        {{ $value['body'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── The Collections — image gallery strip ───────────────────────────────── --}}
<div style="background:#fff; padding:80px 0">
    <div class="container">
        <div style="text-align:center; margin-bottom:48px">
            <p style="font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase;
                      color:var(--fado-gold); margin-bottom:12px">Our Collections</p>
            <h2 style="font-family:Georgia,serif; font-size:clamp(1.5rem,3vw,2rem); font-weight:400;
                       color:var(--fado-deep-green); margin-bottom:16px">
                Stories Told in Silver and Gold
            </h2>
            <p style="color:#888; max-width:560px; margin:0 auto; font-size:.9375rem; line-height:1.7">
                Each collection is inspired by a facet of Irish life — ancient symbols, wild landscapes,
                and the warmth of Irish tradition.
            </p>
        </div>

        {{-- 3-column gallery grid --}}
        <div class="row g-3">
            @foreach([
                ['title' => 'Claddagh',          'sub' => 'Love, loyalty &amp; friendship'],
                ['title' => 'High Crosses',       'sub' => 'Ancient Celtic stonework'],
                ['title' => 'The Garden Collection', 'sub' => 'Flora &amp; fauna of Ireland'],
                ['title' => 'Trinity Knot',       'sub' => 'Eternal Irish symbol'],
                ['title' => 'Newgrange',          'sub' => 'Prehistoric heritage'],
                ['title' => 'Irish Folklore',     'sub' => 'Myth &amp; legend in silver'],
            ] as $col)
            <div class="col-sm-6 col-lg-4">
                <div style="position:relative; aspect-ratio:4/3; background:var(--fado-cream);
                            border-radius:4px; overflow:hidden; cursor:pointer"
                     onmouseover="this.querySelector('.fado-gallery-overlay').style.opacity='1'"
                     onmouseout="this.querySelector('.fado-gallery-overlay').style.opacity='0'">
                    {{-- Placeholder image area --}}
                    <div style="position:absolute; inset:0; display:flex; align-items:center;
                                justify-content:center; color:var(--fado-warm-grey)">
                        <i class="icon icon-image" style="font-size:2.5rem; opacity:.35"></i>
                    </div>
                    {{-- Overlay --}}
                    <div class="fado-gallery-overlay"
                         style="position:absolute; inset:0; background:linear-gradient(transparent 40%, rgba(4,71,5,.75));
                                display:flex; flex-direction:column; justify-content:flex-end; padding:20px;
                                opacity:0; transition:opacity .3s">
                        <p style="font-family:Georgia,serif; font-size:1.0625rem; color:#fff;
                                  font-weight:400; margin-bottom:4px">
                            {!! $col['title'] !!}
                        </p>
                        <p style="font-size:.8125rem; color:rgba(255,255,255,.75); margin:0">
                            {!! $col['sub'] !!}
                        </p>
                    </div>
                    {{-- Always-visible label at bottom --}}
                    <div style="position:absolute; bottom:0; left:0; right:0; padding:14px 16px;
                                background:linear-gradient(transparent, rgba(4,71,5,.55))">
                        <p style="font-family:Georgia,serif; font-size:.9375rem; color:#fff;
                                  margin:0; font-weight:400">{!! $col['title'] !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:40px">
            <a href="{{ route('shop.collections') }}"
               style="display:inline-block; padding:13px 32px; border:1.5px solid var(--fado-deep-green);
                      color:var(--fado-deep-green); text-decoration:none; border-radius:2px;
                      font-size:.9375rem; font-weight:600; transition:all .2s"
               onmouseover="this.style.background='var(--fado-deep-green)'; this.style.color='#fff'"
               onmouseout="this.style.background='transparent'; this.style.color='var(--fado-deep-green)'">
                View All Collections
            </a>
        </div>
    </div>
</div>

{{-- ── Craft process — text right, image left ──────────────────────────────── --}}
<div style="background:var(--fado-soft-white); padding:80px 0">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div style="aspect-ratio:4/3; background:var(--fado-cream); border-radius:4px; overflow:hidden;
                            position:relative">
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
                                flex-direction:column; gap:12px; color:var(--fado-warm-grey)">
                        <i class="icon icon-image" style="font-size:3rem; opacity:.4"></i>
                        <p style="font-size:.8125rem; opacity:.5; margin:0">Craft process image</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <p style="font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase;
                          color:var(--fado-gold); margin-bottom:16px">The Process</p>
                <h2 style="font-family:Georgia,serif; font-size:clamp(1.5rem,3vw,2rem); color:var(--fado-deep-green);
                           font-weight:400; margin-bottom:24px; line-height:1.3">
                    Made to Last a Lifetime
                </h2>
                <p style="font-size:1rem; color:#555; line-height:1.85; margin-bottom:20px">
                    Every FADÓ piece follows the same journey: design, casting, hand-finishing, stone-setting
                    (where applicable), polishing, and finally — hallmarking. No shortcuts, no compromises.
                </p>
                <div style="display:flex; flex-direction:column; gap:20px; margin-bottom:32px">
                    @foreach([
                        ['num' => '01', 'title' => 'Designed in Ireland', 'body' => 'Each design starts with pencil and paper — sketched from the symbols, landscapes, and legends that define Irish identity.'],
                        ['num' => '02', 'title' => 'Cast in precious metals', 'body' => 'Pieces are cast in sterling silver, gold alloys, or platinum. Metal choice affects not just the look but the character of each piece.'],
                        ['num' => '03', 'title' => 'Hand-finished & hallmarked', 'body' => 'Final finishing is done by hand. Every piece is then independently assayed and hallmarked by the Assay Office of Ireland.'],
                    ] as $step)
                    <div style="display:flex; gap:16px; align-items:flex-start">
                        <span style="font-family:Georgia,serif; font-size:1.25rem; font-weight:400;
                                     color:var(--fado-warm-grey); flex-shrink:0; width:32px; margin-top:2px">
                            {{ $step['num'] }}
                        </span>
                        <div>
                            <p style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green); margin-bottom:4px">
                                {{ $step['title'] }}
                            </p>
                            <p style="font-size:.875rem; color:#666; margin:0; line-height:1.7">
                                {{ $step['body'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Consultation CTA ─────────────────────────────────────────────────────── --}}
@if(Setting::get('consultation_enabled', '1'))
<div style="background:var(--fado-deep-green); padding:72px 0; text-align:center">
    <div class="container">
        <i class="icon icon-calendar-blank"
           style="font-size:2rem; color:var(--fado-gold); display:block; margin-bottom:20px"></i>
        <h2 style="font-family:Georgia,serif; font-size:clamp(1.5rem,3vw,2rem); color:#fff;
                   font-weight:400; margin-bottom:16px">
            Book a Personal Consultation
        </h2>
        <p style="color:rgba(255,255,255,.7); font-size:1rem; max-width:520px;
                  margin:0 auto 32px; line-height:1.8">
            {{ Setting::get('consultation_intro_text', 'Book a private consultation with our jewellery specialists — in-store, by phone, or video call.') }}
        </p>
        <a href="{{ route('shop.contact') }}#consultation"
           style="display:inline-block; padding:14px 36px; background:var(--fado-gold);
                  color:#fff; text-decoration:none; border-radius:2px; font-size:.9375rem;
                  font-weight:600; letter-spacing:.03em; transition:opacity .2s"
           onmouseover="this.style.opacity='.85'"
           onmouseout="this.style.opacity='1'">
            Book an Appointment
        </a>
        <div style="margin-top:20px">
            <a href="{{ route('shop.contact') }}"
               style="font-size:.875rem; color:rgba(255,255,255,.6); text-decoration:none;
                      border-bottom:1px solid rgba(255,255,255,.3); transition:color .2s"
               onmouseover="this.style.color='rgba(255,255,255,.9)'"
               onmouseout="this.style.color='rgba(255,255,255,.6)'">
                Or send us a message →
            </a>
        </div>
    </div>
</div>
@endif

@endsection

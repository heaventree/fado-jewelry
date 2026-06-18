@extends('shop.layouts.app')
@php use App\Models\Setting; @endphp

@section('title', 'Contact Us — ' . Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_description', 'Contact ' . Setting::get('store_name', 'FADÓ Jewellery') . ' — send a message or book a personal jewellery consultation.')
@section('canonical', route('shop.contact'))

@section('content')

{{-- ── Page header ──────────────────────────────────────────────────────────── --}}
<div style="background:var(--fado-deep-green); padding:64px 0 56px; text-align:center; position:relative; overflow:hidden">
    <div style="position:absolute; top:-80px; right:-80px; width:360px; height:360px;
                border:1px solid rgba(255,255,255,.06); border-radius:50%; pointer-events:none"></div>
    <div class="container" style="position:relative">
        <p style="font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase;
                  color:var(--fado-gold); margin-bottom:14px">Get in Touch</p>
        <h1 style="font-family:Georgia,serif; font-size:clamp(1.75rem,3.5vw,2.5rem); color:#fff;
                   font-weight:400; margin-bottom:16px; line-height:1.2">
            Contact {{ Setting::get('store_name', 'FADÓ Jewellery') }}
        </h1>
        <p style="color:rgba(255,255,255,.7); font-size:1rem; max-width:480px; margin:0 auto; line-height:1.8">
            We'd love to hear from you. Send us a message or book a personal consultation below.
        </p>
    </div>
</div>

{{-- ── Contact info strip ───────────────────────────────────────────────────── --}}
<div style="background:var(--fado-cream); border-bottom:1px solid var(--fado-warm-grey); padding:32px 0">
    <div class="container">
        <div class="row g-4 justify-content-center text-center">
            @if(Setting::get('contact_email'))
            <div class="col-sm-6 col-md-3">
                <div style="display:flex; flex-direction:column; align-items:center; gap:10px">
                    <div style="width:44px; height:44px; background:var(--fado-deep-green); border-radius:50%;
                                display:flex; align-items:center; justify-content:center">
                        <i class="icon icon-envelope" style="color:#fff; font-size:.9rem"></i>
                    </div>
                    <div>
                        <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                  color:var(--fado-warm-grey); margin-bottom:4px">Email Us</p>
                        <a href="mailto:{{ Setting::get('contact_email') }}"
                           style="font-size:.9rem; color:var(--fado-deep-green); text-decoration:none; font-weight:600">
                            {{ Setting::get('contact_email') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(Setting::get('contact_phone'))
            <div class="col-sm-6 col-md-3">
                <div style="display:flex; flex-direction:column; align-items:center; gap:10px">
                    <div style="width:44px; height:44px; background:var(--fado-deep-green); border-radius:50%;
                                display:flex; align-items:center; justify-content:center">
                        <i class="icon icon-phone" style="color:#fff; font-size:.9rem"></i>
                    </div>
                    <div>
                        <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                  color:var(--fado-warm-grey); margin-bottom:4px">Call Us</p>
                        <a href="tel:{{ preg_replace('/\s+/', '', Setting::get('contact_phone')) }}"
                           style="font-size:.9rem; color:var(--fado-deep-green); text-decoration:none; font-weight:600">
                            {{ Setting::get('contact_phone') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(Setting::get('contact_address'))
            <div class="col-sm-6 col-md-3">
                <div style="display:flex; flex-direction:column; align-items:center; gap:10px">
                    <div style="width:44px; height:44px; background:var(--fado-deep-green); border-radius:50%;
                                display:flex; align-items:center; justify-content:center">
                        <i class="icon icon-map-pin" style="color:#fff; font-size:.9rem"></i>
                    </div>
                    <div>
                        <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                  color:var(--fado-warm-grey); margin-bottom:4px">Find Us</p>
                        <p style="font-size:.9rem; color:var(--fado-deep-green); font-weight:600; margin:0; line-height:1.5">
                            {!! nl2br(e(Setting::get('contact_address'))) !!}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-sm-6 col-md-3">
                <div style="display:flex; flex-direction:column; align-items:center; gap:10px">
                    <div style="width:44px; height:44px; background:var(--fado-deep-green); border-radius:50%;
                                display:flex; align-items:center; justify-content:center">
                        <i class="icon icon-clock" style="color:#fff; font-size:.9rem"></i>
                    </div>
                    <div>
                        <p style="font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase;
                                  color:var(--fado-warm-grey); margin-bottom:4px">Hours</p>
                        <p style="font-size:.9rem; color:var(--fado-deep-green); font-weight:600; margin:0; line-height:1.5">
                            Mon–Sat: 9am – 5:30pm<br>
                            <span style="font-weight:400; color:#888">Sunday: Closed</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="background:var(--fado-near-white); padding:64px 0 96px">
    <div class="container">
        <div class="row g-5">

            {{-- ── LEFT — forms ───────────────────────────────────────────────── --}}
            <div class="col-lg-7">

                {{-- Success notice --}}
                @if(session('consultation_sent'))
                <div style="background:var(--fado-pale-mint); border:1.5px solid var(--fado-green-mid);
                            border-radius:4px; padding:20px 24px; margin-bottom:28px;
                            display:flex; align-items:flex-start; gap:14px">
                    <i class="icon icon-check-circle" style="font-size:1.5rem; color:var(--fado-green-mid); flex-shrink:0; margin-top:2px"></i>
                    <div>
                        <p style="font-size:.9375rem; font-weight:600; color:var(--fado-deep-green); margin-bottom:4px">
                            Message received — thank you!
                        </p>
                        <p style="font-size:.875rem; color:#555; margin:0; line-height:1.6">
                            We'll be in touch within one business day to arrange your consultation.
                        </p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div style="background:#fff3f3; border:1.5px solid #f5c6c6; border-radius:4px;
                            padding:16px 20px; margin-bottom:28px; color:#dc3545; font-size:.875rem">
                    {{ session('error') }}
                </div>
                @endif

                {{-- ── General message form ─────────────────────────────────── --}}
                <div id="contact" style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                            padding:32px; margin-bottom:24px">
                    <h2 style="font-family:Georgia,serif; font-size:1.25rem; font-weight:400;
                               color:var(--fado-deep-green); margin-bottom:8px">Send a Message</h2>
                    <p style="font-size:.875rem; color:#888; margin-bottom:24px; line-height:1.6">
                        Have a question about a piece or need help with an order? We'll get back to you within one business day.
                    </p>

                    @if($errors->any() && !old('form_type') || old('form_type') === 'contact')
                    <div style="background:#fff3f3; border:1px solid #f5c6c6; border-radius:3px;
                                padding:14px 18px; margin-bottom:20px">
                        <ul style="margin:0; padding-left:20px; color:#dc3545; font-size:.8125rem">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('shop.contact.store') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="contact">
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                @include('shop.partials.checkout-field', ['name' => 'name',  'label' => 'Your Name',  'value' => old('name'),  'type' => 'text',  'required' => true])
                            </div>
                            <div class="col-sm-6">
                                @include('shop.partials.checkout-field', ['name' => 'email', 'label' => 'Email Address', 'value' => old('email'), 'type' => 'email', 'required' => true])
                            </div>
                        </div>
                        <div class="mb-3">
                            @include('shop.partials.checkout-field', ['name' => 'phone', 'label' => 'Phone (optional)', 'value' => old('phone'), 'type' => 'tel', 'required' => false])
                        </div>
                        <div class="mb-3">
                            <label style="display:block; font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                          text-transform:uppercase; color:var(--fado-deep-green); margin-bottom:6px">
                                Message <span style="color:#dc3545">*</span>
                            </label>
                            <textarea name="message" rows="5" required maxlength="2000"
                                      style="width:100%; padding:12px 14px;
                                             border:1px solid {{ $errors->has('message') ? '#dc3545' : 'var(--fado-warm-grey)' }};
                                             border-radius:3px; font-size:.9375rem; color:var(--fado-deep-green);
                                             resize:vertical; outline:none; line-height:1.6; font-family:inherit"
                                      onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                      onblur="this.style.borderColor='{{ $errors->has('message') ? '#dc3545' : 'var(--fado-warm-grey)' }}'">{{ old('message') }}</textarea>
                            @error('message')
                            <p style="font-size:.75rem; color:#dc3545; margin-top:4px">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label style="font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                          text-transform:uppercase; color:var(--fado-deep-green); display:block; margin-bottom:10px">
                                Preferred contact method <span style="color:#dc3545">*</span>
                            </label>
                            <div class="d-flex gap-4">
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:.9rem; color:#444">
                                    <input type="radio" name="preferred_contact" value="email"
                                           {{ old('preferred_contact', 'email') === 'email' ? 'checked' : '' }}
                                           style="accent-color:var(--fado-deep-green)">
                                    Email
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:.9rem; color:#444">
                                    <input type="radio" name="preferred_contact" value="phone"
                                           {{ old('preferred_contact') === 'phone' ? 'checked' : '' }}
                                           style="accent-color:var(--fado-deep-green)">
                                    Phone call
                                </label>
                            </div>
                            @error('preferred_contact')
                            <p style="font-size:.75rem; color:#dc3545; margin-top:4px">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                style="padding:14px 36px; background:var(--fado-deep-green); color:#fff;
                                       border:none; border-radius:2px; font-size:.9375rem; font-weight:600;
                                       cursor:pointer; letter-spacing:.03em; transition:background .2s"
                                onmouseover="this.style.background='var(--fado-green-mid)'"
                                onmouseout="this.style.background='var(--fado-deep-green)'">
                            Send Message
                        </button>
                    </form>
                </div>

                {{-- ── Consultation booking form ─────────────────────────────── --}}
                @if(Setting::get('consultation_enabled', '1'))
                <div id="consultation" style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px;
                            padding:32px; border-top:4px solid var(--fado-gold)">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px">
                        <i class="icon icon-calendar-blank" style="font-size:1.375rem; color:var(--fado-gold)"></i>
                        <h2 style="font-family:Georgia,serif; font-size:1.25rem; font-weight:400;
                                   color:var(--fado-deep-green); margin:0">Book a Consultation</h2>
                    </div>
                    <p style="font-size:.875rem; color:#888; margin-bottom:24px; line-height:1.6">
                        {{ Setting::get('consultation_intro_text', 'Book a private consultation with our jewellery specialists — in-store, by phone, or video call.') }}
                    </p>

                    <form method="POST" action="{{ route('shop.contact.store') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="consultation">
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                @include('shop.partials.checkout-field', ['name' => 'name',  'label' => 'Your Name',  'value' => old('name'),  'type' => 'text',  'required' => true])
                            </div>
                            <div class="col-sm-6">
                                @include('shop.partials.checkout-field', ['name' => 'email', 'label' => 'Email Address', 'value' => old('email'), 'type' => 'email', 'required' => true])
                            </div>
                        </div>
                        <div class="mb-3">
                            @include('shop.partials.checkout-field', ['name' => 'phone', 'label' => 'Phone (optional)', 'value' => old('phone'), 'type' => 'tel', 'required' => false])
                        </div>
                        <div class="mb-3">
                            <label style="display:block; font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                          text-transform:uppercase; color:var(--fado-deep-green); margin-bottom:6px">
                                What are you looking for? <span style="color:#dc3545">*</span>
                            </label>
                            <textarea name="message" rows="4" required maxlength="2000"
                                      placeholder="E.g. I'm looking for a custom engagement ring in white gold with an emerald…"
                                      style="width:100%; padding:12px 14px;
                                             border:1px solid {{ $errors->has('message') ? '#dc3545' : 'var(--fado-warm-grey)' }};
                                             border-radius:3px; font-size:.9375rem; color:var(--fado-deep-green);
                                             resize:vertical; outline:none; line-height:1.6; font-family:inherit"
                                      onfocus="this.style.borderColor='var(--fado-green-mid)'"
                                      onblur="this.style.borderColor='{{ $errors->has('message') ? '#dc3545' : 'var(--fado-warm-grey)' }}'">{{ old('message') }}</textarea>
                            @error('message')
                            <p style="font-size:.75rem; color:#dc3545; margin-top:4px">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label style="font-size:.7rem; font-weight:700; letter-spacing:.1em;
                                          text-transform:uppercase; color:var(--fado-deep-green); display:block; margin-bottom:10px">
                                How would you prefer we contact you? <span style="color:#dc3545">*</span>
                            </label>
                            <div class="d-flex gap-4">
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:.9rem; color:#444">
                                    <input type="radio" name="preferred_contact" value="email"
                                           {{ old('preferred_contact', 'email') === 'email' ? 'checked' : '' }}
                                           style="accent-color:var(--fado-deep-green)">
                                    Email
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:.9rem; color:#444">
                                    <input type="radio" name="preferred_contact" value="phone"
                                           {{ old('preferred_contact') === 'phone' ? 'checked' : '' }}
                                           style="accent-color:var(--fado-deep-green)">
                                    Phone call
                                </label>
                            </div>
                        </div>
                        <button type="submit"
                                style="padding:14px 36px; background:var(--fado-gold); color:#fff;
                                       border:none; border-radius:2px; font-size:.9375rem; font-weight:600;
                                       cursor:pointer; letter-spacing:.03em; transition:opacity .2s"
                                onmouseover="this.style.opacity='.85'"
                                onmouseout="this.style.opacity='1'">
                            Request Consultation
                        </button>
                    </form>
                </div>
                @endif

            </div>

            {{-- ── RIGHT — info sidebar ────────────────────────────────────── --}}
            <div class="col-lg-5">
                <div style="position:sticky; top:24px; display:flex; flex-direction:column; gap:20px">

                    {{-- What to expect card --}}
                    <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:28px">
                        <h3 style="font-family:Georgia,serif; font-size:1.0625rem; font-weight:400;
                                   color:var(--fado-deep-green); margin-bottom:20px; padding-bottom:16px;
                                   border-bottom:1px solid var(--fado-cream)">
                            What to Expect
                        </h3>
                        <div style="display:flex; flex-direction:column; gap:16px">
                            @foreach([
                                ['icon' => 'icon-clock',        'title' => 'Reply within 1 business day',  'body' => 'We read every message personally and aim to get back to you quickly.'],
                                ['icon' => 'icon-shield-check', 'title' => 'No obligation',                'body' => 'A consultation is simply a conversation. There\'s no pressure and no commitment required.'],
                                ['icon' => 'icon-star',         'title' => 'Expert advice',               'body' => 'Our team can advise on metal choice, stone selection, sizing, and personalisation options.'],
                                ['icon' => 'icon-gift',         'title' => 'Gift guidance welcome',        'body' => 'Buying a gift for someone? We\'ll help you choose something they\'ll love.'],
                            ] as $item)
                            <div style="display:flex; gap:12px; align-items:flex-start">
                                <div style="width:36px; height:36px; background:var(--fado-cream); border-radius:50%;
                                            display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px">
                                    <i class="icon {{ $item['icon'] }}" style="font-size:.875rem; color:var(--fado-deep-green)"></i>
                                </div>
                                <div>
                                    <p style="font-size:.875rem; font-weight:600; color:var(--fado-deep-green); margin-bottom:2px">
                                        {{ $item['title'] }}
                                    </p>
                                    <p style="font-size:.8125rem; color:#888; margin:0; line-height:1.6">
                                        {{ $item['body'] }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Social links card --}}
                    @if(Setting::get('instagram_url') || Setting::get('facebook_url'))
                    <div style="background:#fff; border:1px solid var(--fado-cream); border-radius:4px; padding:24px">
                        <h3 style="font-family:Georgia,serif; font-size:1rem; font-weight:400;
                                   color:var(--fado-deep-green); margin-bottom:16px">Follow Our Work</h3>
                        <div style="display:flex; flex-direction:column; gap:10px">
                            @if(Setting::get('instagram_url'))
                            <a href="{{ Setting::get('instagram_url') }}" target="_blank" rel="noopener noreferrer"
                               style="display:flex; align-items:center; gap:10px; text-decoration:none; color:#555;
                                      font-size:.875rem; transition:color .2s"
                               onmouseover="this.style.color='var(--fado-deep-green)'"
                               onmouseout="this.style.color='#555'">
                                <i class="icon icon-instagram-logo" style="font-size:1.125rem; color:var(--fado-deep-green)"></i>
                                Instagram
                            </a>
                            @endif
                            @if(Setting::get('facebook_url'))
                            <a href="{{ Setting::get('facebook_url') }}" target="_blank" rel="noopener noreferrer"
                               style="display:flex; align-items:center; gap:10px; text-decoration:none; color:#555;
                                      font-size:.875rem; transition:color .2s"
                               onmouseover="this.style.color='var(--fado-deep-green)'"
                               onmouseout="this.style.color='#555'">
                                <i class="icon icon-facebook-logo" style="font-size:1.125rem; color:var(--fado-deep-green)"></i>
                                Facebook
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Browse CTA card --}}
                    <div style="background:var(--fado-deep-green); border-radius:4px; padding:24px; text-align:center">
                        <p style="font-family:Georgia,serif; font-size:1rem; color:#fff; margin-bottom:12px; font-weight:400">
                            Browse the collection first?
                        </p>
                        <a href="{{ route('shop.jewellery') }}"
                           style="display:inline-block; padding:11px 28px; border:1px solid rgba(255,255,255,.5);
                                  color:#fff; text-decoration:none; border-radius:2px; font-size:.875rem;
                                  font-weight:600; transition:border-color .2s"
                           onmouseover="this.style.borderColor='rgba(255,255,255,.9)'"
                           onmouseout="this.style.borderColor='rgba(255,255,255,.5)'">
                            View Jewellery
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

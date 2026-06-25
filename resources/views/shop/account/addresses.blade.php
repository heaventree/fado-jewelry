@extends('shop.layouts.app')

@section('title', 'My Address — ' . \App\Models\Setting::get('store_name', 'FADÓ Jewellery'))
@section('meta_robots', 'noindex, nofollow')

@section('content')
        <!-- Page Title -->
        <section class="s-page-title">
            <div class="container">
                <div class="content">
                    <h1 class="title-page">My Account</h1>
                    <ul class="breadcrumbs-page">
                        <li><a href="{{ route('shop.home') }}" class="h6 link">Home</a></li>
                        <li class="d-flex"><i class="icon icon-caret-right"></i></li>
                        <li>
                            <h6 class="current-page fw-normal">My address</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- /Page Title -->
        <!-- Account -->
        <section class="flat-spacing">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 d-none d-xl-block">
                        @include('shop.account._sidebar', ['activeNav' => 'addresses'])
                    </div>
                    <div class="col-xl-9">
                        <div class="my-account-content">
                            <h2 class="account-title type-semibold">My Address</h2>
                            @if(session('address_saved'))
                            <div class="alert alert-success mb-4">{{ session('address_saved') }}</div>
                            @endif
                            @if($errors->any())
                            <div class="mb-4">
                                @foreach($errors->all() as $error)
                                <p class="h6 text-danger">{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                            <div class="account-my_address">
                                @foreach($addresses as $addr)
                                <div class="account-address-item file-delete">
                                    <div class="address-item_content">
                                        <h4 class="address-title">{{ $addr->is_default ? 'Default' : $addr->label }}</h4>
                                        <div class="address-info">
                                            <h5 class="fw-semibold">{{ $addr->name }}</h5>
                                            <p class="h6">{{ $addr->line1 }}@if($addr->line2), {{ $addr->line2 }}@endif</p>
                                            <p class="h6">{{ $addr->city }}@if($addr->county), {{ $addr->county }}@endif {{ $addr->postcode }}, {{ $addr->country }}</p>
                                        </div>
                                        @if($addr->phone)
                                        <div class="address-info">
                                            <h5 class="fw-semibold">Phone</h5>
                                            <p class="h6">{{ $addr->phone }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="address-item_action">
                                        <a href="#editAddress{{ $addr->id }}" data-bs-toggle="modal" class="tf-btn animate-btn">
                                            Edit
                                        </a>
                                        <form action="{{ route('shop.account.addresses.destroy', $addr) }}" method="POST" style="display:inline;"
                                              onsubmit="return confirm('Delete this address?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tf-btn style-line remove">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editAddress{{ $addr->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title fw-semibold">Edit Address</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('shop.account.addresses.update', $addr) }}" method="POST" class="form-change_pass">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form_content">
                                                        <div class="cols tf-grid-layout sm-col-2">
                                                            <fieldset>
                                                                <input type="text" name="label" value="{{ $addr->label }}" placeholder="Label (e.g. Home) *" required>
                                                            </fieldset>
                                                            <fieldset>
                                                                <input type="text" name="name" value="{{ $addr->name }}" placeholder="Full name *" required>
                                                            </fieldset>
                                                        </div>
                                                        <fieldset>
                                                            <input type="text" name="line1" value="{{ $addr->line1 }}" placeholder="Address line 1 *" required>
                                                        </fieldset>
                                                        <fieldset>
                                                            <input type="text" name="line2" value="{{ $addr->line2 }}" placeholder="Address line 2">
                                                        </fieldset>
                                                        <div class="cols tf-grid-layout sm-col-2">
                                                            <fieldset>
                                                                <input type="text" name="city" value="{{ $addr->city }}" placeholder="City *" required>
                                                            </fieldset>
                                                            <fieldset>
                                                                <input type="text" name="county" value="{{ $addr->county }}" placeholder="County">
                                                            </fieldset>
                                                        </div>
                                                        <div class="cols tf-grid-layout sm-col-2">
                                                            <fieldset>
                                                                <input type="text" name="postcode" value="{{ $addr->postcode }}" placeholder="Postcode *" required>
                                                            </fieldset>
                                                            <fieldset>
                                                                <input type="text" name="country" value="{{ $addr->country }}" placeholder="Country code (IE) *" required maxlength="2">
                                                            </fieldset>
                                                        </div>
                                                        <fieldset>
                                                            <input type="text" name="phone" value="{{ $addr->phone }}" placeholder="Phone">
                                                        </fieldset>
                                                        <div class="checkbox-wrap">
                                                            <input type="checkbox" name="is_default" value="1" class="tf-check" id="default-edit-{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }}>
                                                            <label for="default-edit-{{ $addr->id }}" class="h6">Set as default</label>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn-submit_form tf-btn animate-btn w-100 fw-bold mt-3">
                                                        Save changes
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($addresses->isEmpty())
                                <p class="h6 text-main mb-4">No saved addresses yet.</p>
                                @endif

                                <!-- Add New Address -->
                                <div class="account-address-item">
                                    <form action="{{ route('shop.account.addresses.store') }}" method="POST" class="form-change_pass w-100">
                                        @csrf
                                        <h4 class="address-title mb-3">Add New Address</h4>
                                        <div class="form_content">
                                            <div class="cols tf-grid-layout sm-col-2">
                                                <fieldset>
                                                    <input type="text" name="label" value="{{ old('label', 'Home') }}" placeholder="Label (e.g. Home) *" required>
                                                </fieldset>
                                                <fieldset>
                                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Full name *" required>
                                                </fieldset>
                                            </div>
                                            <fieldset>
                                                <input type="text" name="line1" value="{{ old('line1') }}" placeholder="Address line 1 *" required>
                                            </fieldset>
                                            <fieldset>
                                                <input type="text" name="line2" value="{{ old('line2') }}" placeholder="Address line 2">
                                            </fieldset>
                                            <div class="cols tf-grid-layout sm-col-2">
                                                <fieldset>
                                                    <input type="text" name="city" value="{{ old('city') }}" placeholder="City *" required>
                                                </fieldset>
                                                <fieldset>
                                                    <input type="text" name="county" value="{{ old('county') }}" placeholder="County">
                                                </fieldset>
                                            </div>
                                            <div class="cols tf-grid-layout sm-col-2">
                                                <fieldset>
                                                    <input type="text" name="postcode" value="{{ old('postcode') }}" placeholder="Postcode *" required>
                                                </fieldset>
                                                <fieldset>
                                                    <input type="text" name="country" value="{{ old('country', 'IE') }}" placeholder="Country code (IE) *" required maxlength="2">
                                                </fieldset>
                                            </div>
                                            <fieldset>
                                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone">
                                            </fieldset>
                                            <div class="checkbox-wrap">
                                                <input type="checkbox" name="is_default" value="1" class="tf-check" id="default-new">
                                                <label for="default-new" class="h6">Set as default</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn-submit_form tf-btn animate-btn w-100 fw-bold mt-3">
                                            Add address
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Account -->
@endsection

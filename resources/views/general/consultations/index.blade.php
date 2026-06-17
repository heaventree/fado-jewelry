@extends('layouts.vertical', ['title' => 'Consultation Enquiries'])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Stat cards ───────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Total Enquiries</h4>
                        <p class="fw-semibold fs-22 mb-0">{{ \App\Models\Consultation::count() }}</p>
                    </div>
                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                        <iconify-icon icon="solar:letter-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Unread</h4>
                        <p class="fw-semibold fs-22 mb-0 text-danger">{{ $unreadCount }}</p>
                    </div>
                    <div class="avatar-md bg-danger bg-opacity-10 rounded">
                        <iconify-icon icon="solar:bell-bold-duotone" class="fs-32 text-danger avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Prefer Email</h4>
                        <p class="fw-semibold fs-22 mb-0 text-info">{{ \App\Models\Consultation::where('preferred_contact', 'email')->count() }}</p>
                    </div>
                    <div class="avatar-md bg-info bg-opacity-10 rounded">
                        <iconify-icon icon="solar:mailbox-bold-duotone" class="fs-32 text-info avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-1">Prefer Phone</h4>
                        <p class="fw-semibold fs-22 mb-0 text-success">{{ \App\Models\Consultation::where('preferred_contact', 'phone')->count() }}</p>
                    </div>
                    <div class="avatar-md bg-success bg-opacity-10 rounded">
                        <iconify-icon icon="solar:phone-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Enquiry table ────────────────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h4 class="card-title flex-grow-1 mb-0">
            Enquiries
            @if($unreadCount > 0)
                <span class="badge bg-danger ms-2">{{ $unreadCount }} new</span>
            @endif
        </h4>

        <form class="d-flex gap-2 flex-wrap" method="GET" action="{{ route('admin.consultations.index') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Name, email or phone…" style="width:220px">

            <select name="filter" class="form-select form-select-sm" style="width:140px"
                    onchange="this.form.submit()">
                <option value="">All enquiries</option>
                <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>Unread only</option>
            </select>

            <button class="btn btn-sm btn-outline-secondary">Search</button>

            @if(request('search') || request('filter'))
                <a href="{{ route('admin.consultations.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-centered">
            <thead class="bg-light-subtle">
                <tr>
                    <th style="width:20px"></th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th style="width:120px">Prefers</th>
                    <th style="width:140px">Received</th>
                    <th style="width:80px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($consultations as $enquiry)
                <tr class="{{ $enquiry->is_read ? '' : 'fw-semibold' }}">
                    <td>
                        @if(! $enquiry->is_read)
                            <span class="badge bg-danger rounded-pill" style="width:8px;height:8px;padding:0"
                                  title="Unread"></span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.consultations.show', $enquiry) }}"
                           class="{{ $enquiry->is_read ? 'text-dark' : 'text-dark' }}">
                            {{ $enquiry->name }}
                        </a>
                        @if(! $enquiry->is_read)
                            <span class="badge bg-danger-subtle text-danger fs-11 ms-1">New</span>
                        @endif
                        @if($enquiry->message)
                            <p class="text-muted fw-normal fs-12 mb-0 text-truncate" style="max-width:280px">
                                {{ $enquiry->message }}
                            </p>
                        @endif
                    </td>
                    <td>
                        <p class="mb-0">{{ $enquiry->email }}</p>
                        @if($enquiry->phone)
                            <p class="mb-0 text-muted fs-12">{{ $enquiry->phone }}</p>
                        @endif
                    </td>
                    <td>
                        @if($enquiry->preferred_contact === 'phone')
                            <span class="badge bg-success-subtle text-success">
                                <iconify-icon icon="solar:phone-broken" class="me-1"></iconify-icon>Phone
                            </span>
                        @else
                            <span class="badge bg-info-subtle text-info">
                                <iconify-icon icon="solar:letter-broken" class="me-1"></iconify-icon>Email
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="text-dark">{{ $enquiry->created_at->format('d M Y') }}</span>
                        <p class="mb-0 text-muted fs-12">{{ $enquiry->created_at->format('H:i') }}</p>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.consultations.show', $enquiry) }}"
                               class="btn btn-light btn-sm" title="View">
                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-16"></iconify-icon>
                            </a>
                            <form action="{{ route('admin.consultations.destroy', $enquiry) }}" method="POST"
                                  onsubmit="return confirm('Delete this enquiry from {{ addslashes($enquiry->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm text-danger" title="Delete">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-16"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No enquiries found.
                        @if(request('search') || request('filter'))
                            <a href="{{ route('admin.consultations.index') }}">Clear filters</a>
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($consultations->hasPages())
    <div class="card-footer border-top">
        {{ $consultations->links() }}
    </div>
    @endif
</div>

@endsection

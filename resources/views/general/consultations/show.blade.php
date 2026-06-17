@extends('layouts.vertical', ['title' => 'Enquiry — ' . $consultation->name])

@section('content')

{{-- ── Header bar ───────────────────────────────────────────────────────────── --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
    <div>
        <h4 class="fw-semibold mb-1">Enquiry from {{ $consultation->name }}</h4>
        <p class="text-muted mb-0 fs-13">
            Received {{ $consultation->created_at->format('d M Y \a\t H:i') }}
            @if($consultation->is_read)
                · <span class="text-success">Read</span>
            @else
                · <span class="text-danger fw-medium">Unread</span>
            @endif
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.consultations.index') }}" class="btn btn-outline-secondary btn-sm">
            <iconify-icon icon="solar:arrow-left-broken" class="align-middle me-1"></iconify-icon>
            Back to Inbox
        </a>
        <form action="{{ route('admin.consultations.destroy', $consultation) }}" method="POST"
              onsubmit="return confirm('Delete this enquiry from {{ addslashes($consultation->name) }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle me-1"></iconify-icon>
                Delete
            </button>
        </form>
    </div>
</div>

<div class="row g-3">

    {{-- ── Left: message ───────────────────────────────────────────────────── --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Message</h4>
            </div>
            <div class="card-body">
                @if($consultation->message)
                    <p class="mb-0 lh-lg" style="white-space:pre-wrap">{{ $consultation->message }}</p>
                @else
                    <p class="text-muted mb-0 fst-italic">No message provided — {{ $consultation->name }} requested a {{ $consultation->preferred_contact_label }} contact.</p>
                @endif
            </div>
        </div>

        {{-- Reply prompt --}}
        <div class="card mt-3">
            <div class="card-body">
                <p class="fw-medium mb-2">
                    <iconify-icon icon="solar:reply-bold-duotone" class="text-primary me-2 fs-18 align-middle"></iconify-icon>
                    Reply to this enquiry
                </p>
                <p class="text-muted fs-13 mb-3">
                    {{ $consultation->name }} prefers to be contacted by
                    <strong>{{ $consultation->preferred_contact_label }}</strong>.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if($consultation->email)
                        <a href="mailto:{{ $consultation->email }}?subject=Your FADÓ Jewellery consultation enquiry"
                           class="btn btn-primary btn-sm">
                            <iconify-icon icon="solar:letter-broken" class="align-middle me-1"></iconify-icon>
                            Email {{ $consultation->name }}
                        </a>
                    @endif
                    @if($consultation->phone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $consultation->phone) }}"
                           class="btn btn-success btn-sm">
                            <iconify-icon icon="solar:phone-broken" class="align-middle me-1"></iconify-icon>
                            Call {{ $consultation->phone }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right: contact details ───────────────────────────────────────────── --}}
    <div class="col-xl-4">

        {{-- Contact info --}}
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Contact Details</h4></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar-md bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                        <span class="fw-bold text-primary fs-16">
                            {{ strtoupper(substr($consultation->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="fw-semibold mb-0">{{ $consultation->name }}</p>
                        <p class="text-muted fs-12 mb-0">Consultation request</p>
                    </div>
                </div>

                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted" style="width:90px">Email</td>
                        <td>
                            <a href="mailto:{{ $consultation->email }}">{{ $consultation->email }}</a>
                        </td>
                    </tr>
                    @if($consultation->phone)
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>
                            <a href="tel:{{ preg_replace('/\s+/', '', $consultation->phone) }}">
                                {{ $consultation->phone }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Prefers</td>
                        <td>
                            @if($consultation->preferred_contact === 'phone')
                                <span class="badge bg-success-subtle text-success">
                                    <iconify-icon icon="solar:phone-broken" class="me-1"></iconify-icon>Phone call
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info">
                                    <iconify-icon icon="solar:letter-broken" class="me-1"></iconify-icon>Email
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Enquiry meta --}}
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Enquiry Details</h4></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted ps-3">Received</td>
                        <td class="pe-3">{{ $consultation->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Status</td>
                        <td class="pe-3">
                            @if($consultation->is_read)
                                <span class="badge bg-success-subtle text-success">Read</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Unread</span>
                            @endif
                        </td>
                    </tr>
                    @if($consultation->read_at)
                    <tr>
                        <td class="text-muted ps-3">Read at</td>
                        <td class="pe-3">{{ $consultation->read_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@extends('layouts.vertical', ['title' => 'Edit Menu: ' . $menu->name])

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-semibold mb-0">Edit Menu: {{ $menu->name }}</h4>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
</div>

{{-- Menu settings --}}
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name) }}" required maxlength="100">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Location</label>
                    <select name="location" class="form-select">
                        <option value="">None</option>
                        <option value="header" @selected(old('location', $menu->location) === 'header')>Header</option>
                        <option value="footer_col_1" @selected(old('location', $menu->location) === 'footer_col_1')>Footer Column 1 (Shopping)</option>
                        <option value="footer_col_2" @selected(old('location', $menu->location) === 'footer_col_2')>Footer Column 2 (Information)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    {{-- Left: Add Items --}}
    <div class="col-lg-5 mb-3">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Add Items</h5></div>
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-custom">Custom</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-pages">Pages</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-collections">Collections</a></li>
                </ul>
                <div class="tab-content pt-3">
                    {{-- Custom Link --}}
                    <div class="tab-pane fade show active" id="tab-custom">
                        <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="custom">
                            <div class="mb-2">
                                <label class="form-label">Label *</label>
                                <input type="text" name="label" class="form-control form-control-sm" required maxlength="200">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">URL</label>
                                <input type="text" name="url" class="form-control form-control-sm" placeholder="/page-url">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Target</label>
                                <select name="target" class="form-select form-select-sm">
                                    <option value="_self">Same window</option>
                                    <option value="_blank">New tab</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Add to Menu</button>
                        </form>
                    </div>

                    {{-- Pages --}}
                    <div class="tab-pane fade" id="tab-pages">
                        <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="page">
                            <input type="hidden" name="target" value="_self">
                            @php
                                $pages = [
                                    ['label' => 'Home', 'url' => '/'],
                                    ['label' => 'About Us', 'url' => '/about'],
                                    ['label' => 'Contact', 'url' => '/contact'],
                                    ['label' => 'FAQ', 'url' => '/faq'],
                                    ['label' => 'Terms', 'url' => '/terms'],
                                    ['label' => 'Privacy', 'url' => '/privacy'],
                                ];
                            @endphp
                            <div class="mb-2" style="max-height:200px;overflow-y:auto">
                                @foreach($pages as $page)
                                <div class="form-check">
                                    <input class="form-check-input page-check" type="checkbox" value="{{ $page['url'] }}" data-label="{{ $page['label'] }}" id="page-{{ $loop->index }}">
                                    <label class="form-check-label" for="page-{{ $loop->index }}">{{ $page['label'] }} <small class="text-muted">({{ $page['url'] }})</small></label>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="label" id="pages-label">
                            <input type="hidden" name="url" id="pages-url">
                            <button type="button" class="btn btn-primary btn-sm" id="add-pages-btn">Add Selected</button>
                        </form>
                    </div>

                    {{-- Categories --}}
                    <div class="tab-pane fade" id="tab-categories">
                        <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST" id="cat-form">
                            @csrf
                            <input type="hidden" name="type" value="category">
                            <input type="hidden" name="target" value="_self">
                            <input type="hidden" name="label" id="cat-label">
                            <input type="hidden" name="reference_id" id="cat-ref">
                            <div class="mb-2" style="max-height:200px;overflow-y:auto">
                                @foreach($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input cat-check" type="checkbox" value="{{ $cat->id }}" data-label="{{ $cat->name }}" id="cat-{{ $cat->id }}">
                                    <label class="form-check-label" for="cat-{{ $cat->id }}">{{ $cat->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-cats-btn">Add Selected</button>
                        </form>
                    </div>

                    {{-- Collections --}}
                    <div class="tab-pane fade" id="tab-collections">
                        <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST" id="col-form">
                            @csrf
                            <input type="hidden" name="type" value="collection">
                            <input type="hidden" name="target" value="_self">
                            <input type="hidden" name="label" id="col-label">
                            <input type="hidden" name="reference_id" id="col-ref">
                            <div class="mb-2" style="max-height:200px;overflow-y:auto">
                                @foreach($collections as $col)
                                <div class="form-check">
                                    <input class="form-check-input col-check" type="checkbox" value="{{ $col->id }}" data-label="{{ $col->name }}" id="col-{{ $col->id }}">
                                    <label class="form-check-label" for="col-{{ $col->id }}">{{ $col->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-cols-btn">Add Selected</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Current Items --}}
    <div class="col-lg-7 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Menu Items</h5>
                @if($menu->items->isNotEmpty())
                <form action="{{ route('admin.menu-items.reorder', $menu) }}" method="POST" id="reorder-form">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">Save Order</button>
                </form>
                @endif
            </div>
            <div class="card-body">
                @if($menu->items->isEmpty())
                    <p class="text-muted text-center py-4">No items yet. Add items from the panel on the left.</p>
                @else
                    <ul class="list-group" id="menu-items-list">
                        @foreach($menu->items as $item)
                        <li class="list-group-item d-flex align-items-center gap-2" data-id="{{ $item->id }}">
                            <span class="drag-handle" style="cursor:grab">☰</span>
                            <div class="flex-grow-1">
                                <strong>{{ $item->label }}</strong>
                                <span class="badge bg-info-subtle text-info ms-1">{{ $item->type }}</span>
                                <small class="text-muted d-block">{{ $item->getResolvedUrl() }}</small>
                            </div>
                            <a href="#editItem{{ $item->id }}" data-bs-toggle="collapse" class="btn btn-light btn-sm" title="Edit">
                                <iconify-icon icon="solar:pen-broken" class="fs-16"></iconify-icon>
                            </a>
                            <form action="{{ route('admin.menu-items.destroy', [$menu, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-light btn-sm text-danger" title="Remove">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-16"></iconify-icon>
                                </button>
                            </form>
                        </li>
                        {{-- Inline edit --}}
                        <li class="list-group-item collapse p-3 bg-light" id="editItem{{ $item->id }}">
                            <form action="{{ route('admin.menu-items.update', [$menu, $item]) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Label</label>
                                        <input type="text" name="label" class="form-control form-control-sm" value="{{ $item->label }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">URL</label>
                                        <input type="text" name="url" class="form-control form-control-sm" value="{{ $item->url }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Target</label>
                                        <select name="target" class="form-select form-select-sm">
                                            <option value="_self" @selected($item->target === '_self')>Self</option>
                                            <option value="_blank" @selected($item->target === '_blank')>Blank</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">Save</button>
                                    </div>
                                </div>
                            </form>
                        </li>
                        {{-- Children --}}
                        @foreach($item->children as $child)
                        <li class="list-group-item d-flex align-items-center gap-2 ps-5" data-id="{{ $child->id }}">
                            <span class="drag-handle" style="cursor:grab">☰</span>
                            <div class="flex-grow-1">
                                ↳ <strong>{{ $child->label }}</strong>
                                <span class="badge bg-info-subtle text-info ms-1">{{ $child->type }}</span>
                                <small class="text-muted d-block">{{ $child->getResolvedUrl() }}</small>
                            </div>
                            <form action="{{ route('admin.menu-items.destroy', [$menu, $child]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-light btn-sm text-danger" title="Remove">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-16"></iconify-icon>
                                </button>
                            </form>
                        </li>
                        @endforeach
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pages: submit one at a time for each checked page
    document.getElementById('add-pages-btn')?.addEventListener('click', function() {
        var checks = document.querySelectorAll('.page-check:checked');
        if (!checks.length) return;
        checks.forEach(function(cb, i) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.menu-items.store", $menu) }}';
            form.innerHTML = '@csrf'.replace(/\n/g,'') +
                '<input type="hidden" name="type" value="page">' +
                '<input type="hidden" name="target" value="_self">' +
                '<input type="hidden" name="label" value="' + cb.dataset.label + '">' +
                '<input type="hidden" name="url" value="' + cb.value + '">';
            document.body.appendChild(form);
            if (i === checks.length - 1) form.submit();
            else {
                fetch(form.action, { method:'POST', body: new FormData(form) });
            }
        });
        // submit last one with page reload
        var last = checks[checks.length - 1];
        document.getElementById('pages-label').value = last.dataset.label;
        document.getElementById('pages-url').value = last.value;
        // Actually just submit all via individual forms
        var firstForm = document.querySelector('#tab-pages form');
        document.getElementById('pages-label').value = checks[0].dataset.label;
        document.getElementById('pages-url').value = checks[0].value;
        firstForm.submit();
    });

    // Categories
    document.getElementById('add-cats-btn')?.addEventListener('click', function() {
        var checked = document.querySelector('.cat-check:checked');
        if (!checked) return;
        document.getElementById('cat-label').value = checked.dataset.label;
        document.getElementById('cat-ref').value = checked.value;
        document.getElementById('cat-form').submit();
    });

    // Collections
    document.getElementById('add-cols-btn')?.addEventListener('click', function() {
        var checked = document.querySelector('.col-check:checked');
        if (!checked) return;
        document.getElementById('col-label').value = checked.dataset.label;
        document.getElementById('col-ref').value = checked.value;
        document.getElementById('col-form').submit();
    });

    // Reorder: collect current order before submit
    document.getElementById('reorder-form')?.addEventListener('submit', function(e) {
        var items = document.querySelectorAll('#menu-items-list > li[data-id]');
        items.forEach(function(li, i) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'items[' + i + '][id]';
            input.value = li.dataset.id;
            e.target.appendChild(input);
            var input2 = document.createElement('input');
            input2.type = 'hidden';
            input2.name = 'items[' + i + '][sort_order]';
            input2.value = i;
            e.target.appendChild(input2);
        });
    });

    // HTML5 drag-and-drop reorder
    var list = document.getElementById('menu-items-list');
    if (list) {
        var dragged = null;
        list.querySelectorAll('li[data-id]').forEach(function(li) {
            li.draggable = true;
            li.addEventListener('dragstart', function(e) {
                dragged = li;
                li.style.opacity = '0.5';
            });
            li.addEventListener('dragend', function() {
                li.style.opacity = '';
                dragged = null;
            });
            li.addEventListener('dragover', function(e) { e.preventDefault(); });
            li.addEventListener('drop', function(e) {
                e.preventDefault();
                if (dragged && dragged !== li) {
                    list.insertBefore(dragged, li);
                }
            });
        });
    }
});
</script>
@endpush

@props(['location', 'wrapper' => 'li'])
@php
    $menu = \App\Models\Menu::getByLocation($location);
@endphp
@if($menu && $menu->items->isNotEmpty())
    @foreach($menu->items as $item)
        @if($wrapper === 'nav')
            <li class="menu-item">
                <a href="{{ $item->getResolvedUrl() }}" class="item-link" @if($item->target === '_blank') target="_blank" @endif>{{ $item->label }}</a>
            </li>
        @else
            <li><a href="{{ $item->getResolvedUrl() }}" class="link h6" @if($item->target === '_blank') target="_blank" @endif>{{ $item->label }}</a></li>
        @endif
    @endforeach
@endif

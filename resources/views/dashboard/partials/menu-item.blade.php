@php
    $isActive = request()->routeIs($item['route'] . '*');
    $hasChildren = isset($item['children']) && count($item['children']) > 0;
    $isOpen =
        $hasChildren &&
        in_array(true, array_map(fn($child) => request()->routeIs($child['route'] . '*'), $item['children']));
@endphp
@role($item['role'])
    <li class="menu-item {{ $isActive || $isOpen ? 'active open' : '' }}">
        @if ($hasChildren)
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base {{ $item['icon'] }}"></i>
                <div data-i18n="{{ $item['label'] }}">{{ $item['label'] }}</div>
                @if (isset($item['badge']))
                    <div class="badge {{ $item['badge']['class'] }} rounded-pill ms-auto">{{ $item['badge']['text'] }}</div>
                @endif
            </a>
            <ul class="menu-sub">
                @foreach ($item['children'] as $child)
                    @include('dashboard.partials.menu-item', ['item' => $child])
                @endforeach
            </ul>
        @else
            <a href="{{ route($item['route']) }}" class="menu-link {{ $isActive ? 'active' : '' }}">
                <i class="menu-icon icon-base {{ $item['icon'] }}"></i>
                <div data-i18n="{{ $item['label'] }}">{{ $item['label'] }}</div>
                @if (isset($item['badge']))
                    <div class="badge {{ $item['badge']['class'] }} rounded-pill ms-auto">{{ $item['badge']['text'] }}
                    </div>
                @endif
            </a>
        @endif
    </li>
@endrole

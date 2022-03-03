@props(['active', 'href', 'title'])
<li class="nav-item {{ $active ? 'menu-open' : '' }}">
    <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }}">
        @if(isset($icon))
            {{ $icon }}
        @else
            <i class="far fa-circle nav-icon"></i>
        @endif
        <p>{{ $title }}</p>
    </a>
</li>


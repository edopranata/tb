@props(['active', 'href', 'title'])
<li class="nav-item {{ ($active && $icon) ? 'menu-open' : '' }}">
    <a href="@if(isset($href)){{$href}}@else#@endif" class="nav-link {{ $active ? 'active' : '' }}">
        @if(isset($icon))
            {{ $icon }}
        @else
            <i class="fas fa-circle nav-icon"></i>
        @endif
        <p>
            {{ $title }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    @isset($slot)
    <ul class="nav nav-treeview">
        {{ $slot }}
    </ul>
    @endisset
</li>

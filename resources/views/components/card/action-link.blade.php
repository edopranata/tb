@props(['btn' => 'primary'])
<a {{ $attributes }} class="btn btn-{{ $btn }} btn-flat" {{ $attributes }}>{{ $slot }}</a>

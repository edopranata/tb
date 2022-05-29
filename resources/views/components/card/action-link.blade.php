@props(['btn' => 'success'])
<a {{ $attributes }} class="btn btn-{{ $btn }} btn-flat" {{ $attributes }}>{{ $slot }}</a>

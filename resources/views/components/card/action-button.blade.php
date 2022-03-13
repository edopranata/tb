@props(['btn' => 'primary'])
<button type="button" class="btn btn-{{ $btn }} btn-flat" {{ $attributes }}>{{ $slot }}</button>

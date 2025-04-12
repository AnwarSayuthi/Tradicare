@if(isset($attributes['href']))
    <a {{ $attributes->merge(['class' => 'btn btn-' . ($variant ?? 'primary') . '-custom']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => 'btn btn-' . ($variant ?? 'primary') . '-custom']) }}>
        {{ $slot }}
    </button>
@endif
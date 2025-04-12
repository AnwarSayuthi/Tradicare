<div class="row g-4">
    @foreach($items as $item)
    <div class="col-md-4">
        {{ $slot }}
    </div>
    @endforeach
</div>
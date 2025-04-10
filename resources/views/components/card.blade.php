<div class="card card-custom">
    @if(isset($header))
        <div class="card-header bg-primary-custom text-white">
            {{ $header }}
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
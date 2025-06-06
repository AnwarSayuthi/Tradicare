<div class="row">
    @foreach($locations as $location)
    <div class="col-md-6 mb-4">
        @include('customer.profile.partials.address-card', ['location' => $location])
    </div>
    @endforeach
</div>
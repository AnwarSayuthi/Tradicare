<div class="tab-pane fade" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Addresses</h5>
            <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                <i class="bi bi-plus-circle me-2"></i> Add New Address
            </button>
        </div>
        <div class="card-body">
            @if($locations->isEmpty())
                @include('customer.profile.partials.empty-addresses')
            @else
                @include('customer.profile.partials.address-list')
            @endif
        </div>
    </div>
</div>
<div class="address-card h-100">
    <div class="address-card-header d-flex justify-content-between">
        <h6 class="mb-1">{{ $location->location_name }}</h6>
        <div class="address-actions">
            <button class="btn btn-sm btn-link p-0 me-2 edit-address-btn" 
                    data-location-id="{{ $location->location_id }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#editAddressModal">
                <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('customer.location.delete', $location->location_id) }}" method="POST" class="d-inline delete-address-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-link p-0 text-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="address-card-body">
        <p class="mb-2">{{ $location->address_line1 }}</p>
        @if($location->address_line2)
            <p class="mb-2">{{ $location->address_line2 }}</p>
        @endif
        <p class="mb-2">{{ $location->city }}, {{ $location->state }} {{ $location->postal_code }}</p>
        @if($location->phone_number)
            <p class="mb-2">{{ $location->phone_number }}</p>
        @endif
    </div>
    
    <div class="address-card-footer">
        @if($location->is_default)
            <span class="badge bg-primary">Default Address</span>
        @endif
    </div>
</div>
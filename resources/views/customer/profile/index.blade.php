@extends('layout')

@section('title', 'My Profile - Tradicare')

@section('content')
<div class="container-lg container-fluid py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mx-auto mb-3">
                            <span class="avatar-initials">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-0">{{ $user->email }}</p>
                    </div>
                    
                    <div class="profile-nav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="#profile-info" data-bs-toggle="tab">
                                    <i class="bi bi-person me-2"></i> Personal Information
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#addresses" data-bs-toggle="tab">
                                    <i class="bi bi-geo-alt me-2"></i> My Addresses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#orders" data-bs-toggle="tab">
                                    <i class="bi bi-bag me-2"></i> Order History
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#appointments" data-bs-toggle="tab">
                                    <i class="bi bi-calendar-check me-2"></i> My Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="tab-content">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="profile-info">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}">
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-check-circle me-2"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Addresses Tab -->
                <div class="tab-pane fade" id="addresses">
                    <div class="card border-0 shadow-sm rounded-lg mb-4">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">My Addresses</h5>
                            <button type="button" class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-circle me-2"></i> Add New Address
                            </button>
                        </div>
                        <div class="card-body">
                            @if($locations->isEmpty())
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5>No addresses found</h5>
                                    <p class="text-muted">Add your first shipping address to make checkout easier.</p>
                                    <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                        <i class="bi bi-plus-circle me-2"></i> Add New Address
                                    </button>
                                </div>
                            @else
                                <div class="row g-4">
                                    @foreach($locations as $location)
                                    <div class="col-md-6">
                                        <div class="card h-100 border {{ $location->is_default ? 'border-primary' : '' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-1">{{ $location->location_name }}</h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <button class="dropdown-item" type="button" data-bs-toggle="modal" 
                                                                        data-bs-target="#editAddressModal{{ $location->location_id }}">
                                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('customer.location.delete', $location->location_id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item text-danger" type="submit" 
                                                                            onclick="return confirm('Are you sure you want to delete this address?')">
                                                                        <i class="bi bi-trash me-2"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <p class="card-text mb-1">{{ $location->address_line1 }}</p>
                                                @if($location->address_line2)
                                                    <p class="card-text mb-1">{{ $location->address_line2 }}</p>
                                                @endif
                                                <p class="card-text mb-1">{{ $location->city }}, {{ $location->state }} {{ $location->postal_code }}</p>
                                                @if($location->phone_number)
                                                    <p class="card-text mb-3">{{ $location->phone_number }}</p>
                                                @endif
                                                
                                                <div class="d-flex flex-wrap gap-2">
                                                    @if($location->is_default)
                                                        <span class="badge bg-primary">Default</span>
                                                    @endif
                                                    @if($location->is_pickup_address)
                                                        <span class="badge bg-info">Pickup</span>
                                                    @endif
                                                    @if($location->is_return_address)
                                                        <span class="badge bg-secondary">Return</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Edit Address Modal -->
                                        <div class="modal fade" id="editAddressModal{{ $location->location_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Address</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('customer.location.update', $location->location_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="location_name{{ $location->location_id }}" class="form-label">Address Name</label>
                                                                <input type="text" class="form-control" id="location_name{{ $location->location_id }}" 
                                                                       name="location_name" value="{{ $location->location_name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="address_line1{{ $location->location_id }}" class="form-label">Address Line 1</label>
                                                                <input type="text" class="form-control" id="address_line1{{ $location->location_id }}" 
                                                                       name="address_line1" value="{{ $location->address_line1 }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="address_line2{{ $location->location_id }}" class="form-label">Address Line 2 (Optional)</label>
                                                                <input type="text" class="form-control" id="address_line2{{ $location->location_id }}" 
                                                                       name="address_line2" value="{{ $location->address_line2 }}">
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="city{{ $location->location_id }}" class="form-label">City</label>
                                                                    <input type="text" class="form-control" id="city{{ $location->location_id }}" 
                                                                           name="city" value="{{ $location->city }}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="state{{ $location->location_id }}" class="form-label">State</label>
                                                                    <input type="text" class="form-control" id="state{{ $location->location_id }}" 
                                                                           name="state" value="{{ $location->state }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="postal_code{{ $location->location_id }}" class="form-label">Postal Code</label>
                                                                    <input type="text" class="form-control" id="postal_code{{ $location->location_id }}" 
                                                                           name="postal_code" value="{{ $location->postal_code }}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="phone_number{{ $location->location_id }}" class="form-label">Phone Number</label>
                                                                    <input type="text" class="form-control" id="phone_number{{ $location->location_id }}" 
                                                                           name="phone_number" value="{{ $location->phone_number }}">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="is_default{{ $location->location_id }}" 
                                                                           name="is_default" {{ $location->is_default ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="is_default{{ $location->location_id }}">
                                                                        Set as default address
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="is_pickup_address{{ $location->location_id }}" 
                                                                           name="is_pickup_address" {{ $location->is_pickup_address ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="is_pickup_address{{ $location->location_id }}">
                                                                        Use as pickup address
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="is_return_address{{ $location->location_id }}" 
                                                                           name="is_return_address" {{ $location->is_return_address ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="is_return_address{{ $location->location_id }}">
                                                                        Use as return address
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Orders Tab -->
                <div class="tab-pane fade" id="orders">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0">Order History</h5>
                        </div>
                        <div class="card-body">
                            <!-- Order history content will go here -->
                            <p class="text-center py-4">Your order history will be displayed here.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Appointments Tab -->
                <div class="tab-pane fade" id="appointments">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0">My Appointments</h5>
                        </div>
                        <div class="card-body">
                            <!-- Appointments content will go here -->
                            <p class="text-center py-4">Your appointments will be displayed here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customer.location.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Address Name</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" required>
                        <div class="form-text">E.g., Home, Office, etc.</div>
                    </div>
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                    </div>
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_pickup_address" name="is_pickup_address">
                            <label class="form-check-label" for="is_pickup_address">
                                Use as pickup address
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_return_address" name="is_return_address">
                            <label class="form-check-label" for="is_return_address">
                                Use as return address
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Add Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: var(--primary);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .avatar-initials {
        color: white;
        font-size: 2rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .profile-nav .nav-link {
        color: var(--secondary);
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .profile-nav .nav-link:hover {
        background-color: rgba(73, 54, 40, 0.05);
    }
    
    .profile-nav .nav-link.active {
        background-color: rgba(73, 54, 40, 0.1);
        color: var(--primary);
        font-weight: 500;
    }
    
    .tab-content {
        min-height: 400px;
    }
    
    .badge.bg-soft-primary {
        background-color: rgba(73, 54, 40, 0.1);
        color: var(--primary);
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .profile-nav {
            margin-bottom: 2rem;
        }
        
        .profile-nav .nav {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .profile-nav .nav-item {
            width: auto;
        }
    }
    
    @media (max-width: 767.98px) {
        .avatar-circle {
            width: 60px;
            height: 60px;
        }
        
        .avatar-initials {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
@extends('layout')

@section('title', 'My Profile - Tradicare')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <i class="bi bi-person"></i>
                </div>
                <h5 class="text-center mt-3">{{ auth()->user()->name }}</h5>
                <p class="text-center text-muted">{{ auth()->user()->email }}</p>
                
                <div class="profile-nav">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">
                                <i class="bi bi-person me-2"></i> Personal Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="addresses-tab" data-bs-toggle="tab" href="#addresses" role="tab">
                                <i class="bi bi-geo-alt me-2"></i> My Addresses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                                <i class="bi bi-bag me-2"></i> My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="appointments-tab" data-bs-toggle="tab" href="#appointments" role="tab">
                                <i class="bi bi-calendar-check me-2"></i> My Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                                <i class="bi bi-shield-lock me-2"></i> Change Password
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <div class="tab-content" id="profileTabContent">
                <!-- Personal Info Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->tel_number }}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="bi bi-check-circle me-2"></i> Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Addresses Tab -->
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
                                <div class="text-center py-4">
                                    <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No addresses found</h5>
                                    <p class="text-muted mb-4">You haven't added any addresses yet.</p>
                                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                        <i class="bi bi-plus-circle me-2"></i> Add New Address
                                    </button>
                                </div>
                            @else
                                <div class="row">
                                    @foreach($locations as $location)
                                    <div class="col-md-6 mb-4">
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
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Orders Tab -->
                <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">My Orders</h5>
                        </div>
                        <div class="card-body">
                            <div class="order-tabs mb-4">
                                <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-orders" type="button" role="tab" aria-controls="all-orders" aria-selected="true">All</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="to-pay-tab" data-bs-toggle="tab" data-bs-target="#to-pay" type="button" role="tab" aria-controls="to-pay" aria-selected="false">To Pay</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="to-ship-tab" data-bs-toggle="tab" data-bs-target="#to-ship" type="button" role="tab" aria-controls="to-ship" aria-selected="false">To Ship</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="to-receive-tab" data-bs-toggle="tab" data-bs-target="#to-receive" type="button" role="tab" aria-controls="to-receive" aria-selected="false">To Receive</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="tab-content" id="orderTabsContent">
                                <!-- All Orders Tab -->
                                <div class="tab-pane fade show active" id="all-orders" role="tabpanel" aria-labelledby="all-tab">
                                    @if(empty($orders) || count($orders) == 0)
                                        <div class="text-center py-5">
                                            <i class="bi bi-bag-x text-muted" style="font-size: 3rem;"></i>
                                            <h5 class="mt-3">No orders found</h5>
                                            <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                                            <a href="{{ route('customer.products.index') }}" class="btn btn-primary-custom">
                                                <i class="bi bi-shop me-2"></i> Start Shopping
                                            </a>
                                        </div>
                                    @else
                                        @foreach($orders as $order)
                                        <div class="card shadow-sm border-0 rounded-lg mb-4 order-card">
                                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                                <div>
                                                    <span class="text-muted">Order #{{ $order->order_id }}</span>
                                                    <span class="mx-2">|</span>
                                                    <span class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                                                </div>
                                                <span class="badge {{ getStatusBadgeClass($order->status) }}">{{ ucfirst($order->status) }}</span>
                                            </div>
                                            <div class="card-body">
                                                @foreach($order->orderItems->take(2) as $item)
                                                <div class="order-item d-flex align-items-center mb-3">
                                                    <div class="order-item-img me-3">
                                                        <img src="{{ asset('storage/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                                                    </div>
                                                    <div class="order-item-details flex-grow-1">
                                                        <h6 class="mb-0">{{ $item->product->product_name }}</h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                            <span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                
                                                @if($order->orderItems->count() > 2)
                                                    <div class="more-items text-muted">
                                                        <small>+ {{ $order->orderItems->count() - 2 }} more items</small>
                                                    </div>
                                                @endif
                                                
                                                <div class="order-summary mt-3 pt-3 border-top">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="text-muted">Total:</span>
                                                            <span class="fw-bold ms-2">RM{{ number_format($order->total_amount, 2) }}</span>
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('customer.profile.orders.show', $order->order_id) }}" class="btn btn-outline-primary btn-sm">
                                                                View Details
                                                            </a>
                                                            
                                                            @if($order->status === 'processing')
                                                                <button class="btn btn-outline-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->order_id }}">
                                                                    Cancel Order
                                                                </button>
                                                            @endif
                                                            
                                                            @if($order->status === 'shipped')
                                                                <form action="{{ route('customer.profile.orders.receive', $order->order_id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary-custom btn-sm ms-2">
                                                                        Received
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Cancel Order Modal -->
                                        <div class="modal fade" id="cancelOrderModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="cancelOrderModalLabel{{ $order->order_id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelOrderModalLabel{{ $order->order_id }}">Cancel Order #{{ $order->order_id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <form action="{{ route('customer.profile.orders.cancel', $order->order_id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Cancel Order</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                        <div class="d-flex justify-content-center mt-4">
                                            {{ $orders->links() }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- To Pay Tab -->
                                <div class="tab-pane fade" id="to-pay" role="tabpanel" aria-labelledby="to-pay-tab">
                                    @php
                                        $toPayOrders = isset($orders) ? $orders->where('payment.status', 'pending') : collect([]);
                                    @endphp
                                    
                                    @if($toPayOrders->isEmpty())
                                        <div class="text-center py-5">
                                            <i class="bi bi-credit-card text-muted" style="font-size: 3rem;"></i>
                                            <h5 class="mt-3">No pending payments</h5>
                                            <p class="text-muted">You don't have any orders waiting for payment.</p>
                                        </div>
                                    @else
                                        @foreach($toPayOrders as $order)
                                        <!-- Order card content similar to All Orders tab -->
                                        @endforeach
                                    @endif
                                </div>
                                
                                <!-- Other order status tabs with similar structure -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Appointments Tab -->
                <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">My Appointments</h5>
                            <a href="{{ route('customer.appointment.create') }}" class="btn btn-sm btn-primary-custom">
                                <i class="bi bi-plus-circle me-2"></i> Book New Appointment
                            </a>
                        </div>
                        <div class="card-body">
                            @if(count($appointments ?? []) == 0)
                                <div class="text-center py-5">
                                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No appointments found</h5>
                                    <p class="text-muted mb-4">You haven't booked any appointments yet.</p>
                                    <a href="{{ route('customer.appointment.create') }}" class="btn btn-primary-custom">
                                        <i class="bi bi-calendar-plus me-2"></i> Book an Appointment
                                    </a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>Date & Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="service-icon me-3">
                                                            <i class="bi {{ $appointment->service->icon ?? 'bi-calendar-check' }}"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $appointment->service->service_name }}</h6>
                                                            <small class="text-muted">{{ $appointment->service->duration_minutes }} mins</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ getStatusBadgeClass($appointment->status) }}">{{ ucfirst($appointment->status) }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('customer.appointment.show', $appointment->appointment_id) }}" class="btn btn-sm btn-outline-primary me-2">
                                                            View
                                                        </a>
                                                        
                                                        @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelAppointmentModal{{ $appointment->appointment_id }}">
                                                                Cancel
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.password.change') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="bi bi-shield-check me-2"></i> Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.location.add') }}" method="POST" id="addAddressForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Address Name</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" placeholder="Home, Office, etc." required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number (Optional)</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addAddressForm" class="btn btn-primary-custom">Save Address</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.location.update', 0) }}" method="POST" id="editAddressForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="location_id" id="edit_location_id">
                    
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Address Name</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" placeholder="Home, Office, etc." required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number (Optional)</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editAddressForm" class="btn btn-primary-custom">Update Address</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .profile-sidebar {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 25px;
        height: 100%;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .profile-avatar i {
        font-size: 2.5rem;
        color: var(--primary);
    }
    
    .profile-nav {
        margin-top: 20px;
    }
    
    .profile-nav .nav-link {
        color: #666;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }
        
    .profile-nav .nav-link:hover {
        background: #e2d5c9;
        color: #6b4c35;
    }
        
    .profile-nav .nav-link.active {
        background: #6b4c35;
        color: white;
    }
        
    .profile-nav .nav-link i {
        font-size: 1.2rem;
        margin-right: 10px;
        color: #6b4c35;
    }
    
    .profile-nav .nav-link.active i {
        color: white;
    }
    
    .btn-primary-custom {
        background-color: #6b4c35;
        border-color: #6b4c35;
        color: white;
    }
    
    .btn-primary-custom:hover {
        background-color: #5a3f2c;
        border-color: #5a3f2c;
        color: white;
    }
    
    .address-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }
    
    .address-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    
    .address-card-header {
        margin-bottom: 15px;
    }
    
    .address-card-body {
        color: #666;
        font-size: 0.95rem;
    }
    
    .address-card-footer {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    
    .order-tabs .nav-link {
        color: #666;
        font-weight: 500;
    }
    
    .order-tabs .nav-link.active {
        color: var(--primary);
        font-weight: 600;
    }
    
    .order-card {
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .order-item-img {
        width: 60px;
        height: 60px;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .order-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .more-items {
        text-align: center;
        padding: 5px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    
    .service-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-icon i {
        font-size: 1.2rem;
        color: var(--primary);
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .profile-sidebar {
            margin-bottom: 30px;
        }
    }
    
    @media (max-width: 767px) {
        .order-tabs {
            overflow-x: auto;
            white-space: nowrap;
            flex-wrap: nowrap;
            padding-bottom: 10px;
        }
        
        .order-tabs .nav-item {
            display: inline-block;
        }
    }
</style>
@endsection

@section('js')
<script>
    // Handle tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Fix for the personal info tab (it's using wrong ID)
        const personalTab = document.getElementById('personal-tab');
        if (personalTab) {
            personalTab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('#profile').classList.add('show', 'active');
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    if (pane.id !== 'profile') {
                        pane.classList.remove('show', 'active');
                    }
                });
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });
                this.classList.add('active');
            });
        }
        
        // Make sure all tab links work correctly
        document.querySelectorAll('.profile-nav .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('href');
                
                // Remove active class from all tabs
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                // Add active class to current tab
                document.querySelector(target).classList.add('show', 'active');
                
                // Update active state on nav links
                document.querySelectorAll('.profile-nav .nav-link').forEach(navLink => {
                    navLink.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    });

    // Handle edit address modal
    document.querySelectorAll('.edit-address-btn').forEach(button => {
        button.addEventListener('click', function() {
            const locationId = this.getAttribute('data-location-id');
            document.getElementById('edit_location_id').value = locationId;
            
            // Fetch the address details via AJAX
            fetch(`/customer/profile/location/${locationId}/get`)
                .then(response => response.json())
                .then(data => {
                    // Populate the form fields
                    document.querySelector('#editAddressForm #location_name').value = data.location_name;
                    document.querySelector('#editAddressForm #address_line1').value = data.address_line1;
                    document.querySelector('#editAddressForm #address_line2').value = data.address_line2 || '';
                    document.querySelector('#editAddressForm #city').value = data.city;
                    document.querySelector('#editAddressForm #state').value = data.state;
                    document.querySelector('#editAddressForm #postal_code').value = data.postal_code;
                    document.querySelector('#editAddressForm #phone_number').value = data.phone_number || '';
                    document.querySelector('#editAddressForm #is_default').checked = data.is_default;
                })
                .catch(error => {
                    console.error('Error fetching address details:', error);
                });
            
            // Update the form action URL
            const form = document.getElementById('editAddressForm');
            form.action = form.action.replace(/\/\d+$/, '/' + locationId);
        });
    });
    
    // Confirm delete address
    document.querySelectorAll('.delete-address-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this address?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
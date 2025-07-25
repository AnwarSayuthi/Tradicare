@extends('admin_layout')

@section('title', 'Order Details - Tradicare Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Order #{{ $order->order_id }}</h4>
                        <p class="text-muted mb-0">Placed on {{ date('M d, Y, h:i A', strtotime($order->order_date)) }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="bi bi-pencil me-1"></i> Update Status
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-8 p-4 border-end">
                            <!-- Order Status Timeline -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0">Order Status</h5>
                                    <span class="badge rounded-pill px-3 py-2
                                        @if($order->status == 'processing') bg-primary
                                        @elseif($order->status == 'shipped') bg-info
                                        @elseif($order->status == 'delivered' || $order->status == 'completed') bg-success
                                        @elseif($order->status == 'cancelled' || $order->status == 'refunded') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div class="order-timeline position-relative mt-4 ps-4">
                                    @php
                                        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
                                        $currentStatusIndex = array_search($order->status, $statuses);
                                        if ($currentStatusIndex === false) $currentStatusIndex = -1;
                                    @endphp

                                    @foreach($statuses as $index => $status)
                                        <div class="timeline-item pb-4 position-relative">
                                            <div class="timeline-icon position-absolute start-0 translate-middle-x
                                                @if($index <= $currentStatusIndex) bg-primary @else bg-light border @endif">
                                                @if($index <= $currentStatusIndex)
                                                    <i class="bi bi-check text-white"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content ps-4">
                                                <h6 class="mb-1 @if($index <= $currentStatusIndex) fw-bold @endif">
                                                    {{ ucfirst($status) }}
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    @if($index <= $currentStatusIndex)
                                                        @if($index == $currentStatusIndex)
                                                            Current status
                                                        @else
                                                            Completed
                                                        @endif
                                                    @else
                                                        Pending
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Items -->
                            <h5 class="fw-semibold mb-3 border-bottom pb-3">Order Items</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" width="60">Image</th>
                                            <th scope="col">Product</th>
                                            <th scope="col" class="text-center">Quantity</th>
                                            <th scope="col" class="text-end">Price</th>
                                            <th scope="col" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="product-img-small bg-light rounded">
                                                        @if($item->product && $item->product->product_image)
                                                            <img src="{{ $item->product->getImageUrl() }}" alt="{{ $item->product->product_name }}" class="img-thumbnail me-3" width="60">
                                                        @else
                                                            <div class="placeholder-image me-3">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    @if($item->product)
                                                        <a href="{{ route('admin.products.show', $item->product->product_id) }}"
                                                            class="text-decoration-none small">View product</a>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end"><span class="fw-medium">RM{{ number_format($item->unit_price, 2) }}</span></td>
                                                <td class="text-end">RM {{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-top">
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                            <td class="text-end">RM {{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Shipping:</td>
                                            <td class="text-end">RM {{ number_format($order->shipping_fee, 2) }}</td>
                                        </tr>
                                        @if($order->discount > 0)
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Discount:</td>
                                            <td class="text-end text-danger">-RM {{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold fs-5">RM {{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-4 p-4">
                            <!-- Customer Information -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3 border-bottom pb-3">Customer Information</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-light p-2 me-3">
                                        <i class="bi bi-person fs-5 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $order->user->name }}</h6>
                                        <p class="mb-0 text-muted small">
                                            {{-- <a href="{{ route('admin.customers.show', $order->user->id) }}" class="text-decoration-none">
                                                View customer profile
                                            </a> --}}
                                        </p>
                                    </div>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="text-muted small">Email:</span>
                                    <p class="mb-0">{{ $order->user->email }}</p>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="text-muted small">Phone:</span>
                                    <p class="mb-0">{{ $order->user->tel_number ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            <div class="mb-4">
                                @if($order->location)
                                <div class="info-card p-3 rounded-3 bg-light mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-pin-map text-primary me-2"></i>
                                        <h6 class="mb-0">Shipping Information</h6>
                                    </div>
                                    <p class="mb-0">
                                        <strong>{{ $order->location->location_name }}</strong><br>
                                        {{ $order->location->address_line1 }}
                                        @if($order->location->address_line2)
                                            , {{ $order->location->address_line2 }}
                                        @endif
                                        <br>
                                        {{ $order->location->city }}, {{ $order->location->state }} {{ $order->location->postal_code }}
                                        @if($order->location->phone_number)
                                            <br>Phone: {{ $order->location->phone_number }}
                                        @endif
                                    </p>
                                </div>
                                @endif
                                <div class="info-card p-3 rounded-3 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-truck text-primary me-2"></i>
                                        <h6 class="mb-0">Shipping Method</h6>
                                    </div>
                                    <p class="mb-0">{{ $order->shipping_method ?? 'Standard Shipping' }}</p>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3 border-bottom pb-3">Payment Information</h5>
                                <div class="info-card p-3 rounded-3 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-credit-card text-primary me-2"></i>
                                        <h6 class="mb-0">Payment Details</h6>
                                    </div>

                                    @if($order->payments->count() > 0)
                                        <!-- Display the most recent payment information -->
                                        @php
                                            $latestPayment = $order->payments->sortByDesc('payment_date')->first();
                                        @endphp

                                        <div class="info-item mb-2">
                                            <span class="text-muted small">Method:</span>
                                            <p class="mb-0">{{ $latestPayment->payment_method ?? 'N/A' }}</p>
                                        </div>
                                        <div class="info-item mb-2">
                                            <span class="text-muted small">Status:</span>
                                            <span class="badge bg-{{ $latestPayment->status == \App\Models\Payment::STATUS_COMPLETED ? 'success' : 'warning' }}">
                                                {{ ucfirst($latestPayment->status ?? 'pending') }}
                                            </span>
                                        </div>
                                        @if($latestPayment->transaction_id)
                                        <div class="info-item mb-2">
                                            <span class="text-muted small">Transaction ID:</span>
                                            <p class="mb-0">{{ $latestPayment->transaction_id }}</p>
                                        </div>
                                        @endif
                                        <div class="info-item mb-0">
                                            <span class="text-muted small">Date:</span>
                                            <p class="mb-0">{{ $latestPayment->payment_date->format('M d, Y, h:i A') }}</p>
                                        </div>

                                        @if($order->payments->count() > 1)
                                            <div class="mt-3 pt-2 border-top">
                                                <a href="#" data-bs-toggle="collapse" data-bs-target="#allPayments" class="text-decoration-none small">
                                                    <i class="bi bi-chevron-down me-1"></i> View all payments ({{ $order->payments->count() }})
                                                </a>

                                                <div class="collapse mt-2" id="allPayments">
                                                    @foreach($order->payments->sortByDesc('payment_date') as $payment)
                                                        <div class="card card-body bg-light mb-2 p-2 small">
                                                            <div class="d-flex justify-content-between">
                                                                <span>{{ $payment->payment_date->format('M d, Y') }}</span>
                                                                <span class="badge bg-{{ $payment->status == \App\Models\Payment::STATUS_COMPLETED ? 'success' : 'warning' }}">
                                                                    {{ ucfirst($payment->status) }}
                                                                </span>
                                                            </div>
                                                            <div>Method: {{ $payment->payment_method }}</div>
                                                            <div>Amount: RM {{ number_format($payment->amount, 2) }}</div>
                                                            @if($payment->transaction_id)
                                                                <div>Transaction ID: {{ $payment->transaction_id }}</div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <!-- No payments found -->
                                        <div class="text-muted">
                                            <p class="mb-0">No payment information available.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Notes -->
                            @if($order->notes)
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3 border-bottom pb-3">Order Notes</h5>
                                <div class="p-3 rounded-3 bg-light">
                                    <p class="mb-0">{{ $order->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('modals')
<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="updateStatusModalLabel">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.update-status', $order->order_id) }}" method="POST" id="updateStatusForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Order Status</label>
                        <select class="form-select" id="status" name="status" required onchange="toggleTrackingField()">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <div class="mb-3" id="trackingNumberField" style="display: {{ in_array($order->status, ['shipped', 'delivered']) ? 'block' : 'none' }};">
                        <label for="tracking_number" class="form-label fw-semibold">
                            Tracking Number
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number"
                               value="{{ $order->tracking_number ?? '' }}" placeholder="Enter tracking number">
                        <small class="text-muted">Enter the shipping tracking number</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="updateStatusBtn">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@section('css')
<style>
    /* Order Timeline Styling */
    .order-timeline {
        border-left: 2px solid #e9ecef;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-item:last-child {
        padding-bottom: 0 !important;
    }

    .timeline-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .timeline-icon i {
        font-size: 12px;
    }

    /* Product Image */
    .product-img-small {
        width: 50px;
        height: 50px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-img-small img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    /* Info Cards */
    .info-card {
        transition: all 0.3s ease;
        border-left: 4px solid var(--bs-primary);
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .info-item p {
        font-weight: 500;
    }

    /* Buttons */
    .btn {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        padding: 0.5rem 1.25rem;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.4);
    }

    .btn-primary:hover {
        box-shadow: 0 0.5rem 1rem rgba(var(--bs-primary-rgb), 0.2);
    }

    /* Card */
    .card {
        transition: all 0.3s ease;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }

    /* Badge */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* Modal */
    .modal-content {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .modal-header {
        background-color: #f8f9fa;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-header .d-flex {
            margin-top: 1rem;
            width: 100%;
        }

        .card-header .btn {
            flex: 1;
        }

        .row.g-0 {
            flex-direction: column;
        }

        .col-lg-8 {
            border-right: none !important;
            border-bottom: 1px solid #dee2e6;
        }
    }

    @media (max-width: 767.98px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .product-img-small {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 575.98px) {
        .card-body .p-4 {
            padding: 1rem !important;
        }

        h4.fw-bold {
            font-size: 1.25rem;
        }

        h5.fw-semibold {
            font-size: 1rem;
        }

        .timeline-content {
            padding-left: 1rem !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
    const updateStatusModalElement = document.getElementById('updateStatusModal');
    const updateStatusForm = document.getElementById('updateStatusForm');
    const updateStatusBtn = document.getElementById('updateStatusBtn');

    // Create Bootstrap modal instance
    let updateStatusModal = null;
    if (updateStatusModalElement) {
        updateStatusModal = new bootstrap.Modal(updateStatusModalElement, {
            backdrop: 'static',
            keyboard: false
        });
    }

    // Handle modal show event
    if (updateStatusModalElement) {
        updateStatusModalElement.addEventListener('show.bs.modal', function() {
            // Reset form state when modal opens
            if (updateStatusBtn) {
                updateStatusBtn.disabled = false;
                updateStatusBtn.innerHTML = '<i class="bi bi-pencil me-1"></i> Update Status';
            }
            // Initialize tracking field visibility
            toggleTrackingField();
        });

        // Handle modal hidden event
        updateStatusModalElement.addEventListener('hidden.bs.modal', function() {
            // Reset form state when modal closes
            if (updateStatusBtn) {
                updateStatusBtn.disabled = false;
                updateStatusBtn.innerHTML = '<i class="bi bi-pencil me-1"></i> Update Status';
            }
            // Reset form if needed
            if (updateStatusForm) {
                updateStatusForm.classList.remove('was-validated');
            }
        });
    }

    // Handle form submission
    if (updateStatusForm) {
        updateStatusForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            const statusSelect = document.getElementById('status');
            const trackingInput = document.getElementById('tracking_number');

            if (!statusSelect || !statusSelect.value) {
                alert('Please select a status.');
                return false;
            }

            // Validate tracking number for shipped/delivered orders
            const selectedStatus = statusSelect.value;
            if ((selectedStatus === 'shipped' || selectedStatus === 'delivered')) {
                if (!trackingInput || !trackingInput.value.trim()) {
                    alert('Please enter a tracking number for shipped/delivered orders.');
                    trackingInput?.focus();
                    return false;
                }
            }

            // Show loading state
            if (updateStatusBtn) {
                updateStatusBtn.disabled = true;
                updateStatusBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Updating...';
            }

            // Submit form
            this.submit();
        });
    }

    // Initialize tracking field on page load
    toggleTrackingField();
});

// Toggle tracking number field based on status
function toggleTrackingField() {
    const statusSelect = document.getElementById('status');
    const trackingField = document.getElementById('trackingNumberField');
    const trackingInput = document.getElementById('tracking_number');

    if (!statusSelect || !trackingField) return;

    const selectedStatus = statusSelect.value;

    if (selectedStatus === 'shipped' || selectedStatus === 'delivered') {
        trackingField.style.display = 'block';
        if (trackingInput) {
            trackingInput.required = true;
        }
    } else {
        trackingField.style.display = 'none';
        if (trackingInput) {
            trackingInput.required = false;
            trackingInput.value = '';
        }
    }
}
</script>
@endsection

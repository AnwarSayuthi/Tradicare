@extends('layout')

@section('title', 'My Profile - Tradicare')

@section('css')
<style>
    /* Profile Sidebar Styling */
    .profile-sidebar {
        position: sticky;
        top: 20px;
    }
    
    .profile-avatar {
        position: relative;
        display: inline-block;
    }
    
    .avatar-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 3px solid var(--primary);
    }
    
    .avatar-placeholder {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .avatar-placeholder i {
        font-size: 2rem;
        color: white;
    }
    
    .profile-name {
        font-weight: 600;
        color: var(--dark);
    }
    
    .profile-email {
        font-size: 0.9rem;
    }
    
    /* Custom Nav Pills */
    .nav-pills-custom {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .nav-pills-custom .nav-link {
        color: #6c757d;
        border-radius: 12px;
        padding: 12px 16px;
        margin: 2px 0;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    
    .nav-pills-custom .nav-link i {
        margin-right: 8px;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }
    
    .nav-pills-custom .nav-link:hover {
        background: rgba(255, 255, 255, 0.8);
        color: var(--primary);
        transform: translateX(5px);
    }
    
    .nav-pills-custom .nav-link.active {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white !important;
        box-shadow: 0 4px 12px rgba(73, 54, 40, 0.3);
    }
    
    .nav-pills-custom .nav-link.active i {
        transform: scale(1.1);
    }
    
    /* Order Card Styling */
    .order-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .order-item-img {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    .placeholder-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 6px;
    }
    
    .placeholder-image i {
        font-size: 1.5rem;
        color: #adb5bd;
    }
    
    .product-name {
        font-weight: 600;
        color: var(--dark);
        line-height: 1.3;
    }
    
    .quantity-info {
        color: #6c757d;
        font-weight: 500;
    }
    
    .price-info {
        color: var(--primary);
        font-weight: 600;
        font-size: 1.05rem;
    }
    
    .order-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #ffc107, #ffb300);
        color: #000;
    }
    
    .badge-processing {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    
    .badge-shipped {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
    }
    
    .badge-delivered {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        color: white;
    }
    
    .badge-cancelled {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .order-id {
        font-weight: 600;
        color: var(--primary);
    }
    
    .order-date {
        font-size: 0.9rem;
    }
    
    .total-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .order-actions .btn {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    /* Empty State */
    .empty-state-icon {
        opacity: 0.7;
    }
    
    /* Tab Content */
    .nav-tabs {
        border-bottom: 2px solid #f1f3f4;
        margin-bottom: 1.5rem;
    }
    
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 12px 20px;
        font-weight: 500;
        background: transparent;
        border-radius: 0;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: var(--primary);
        background: rgba(73, 54, 40, 0.05);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary);
        background-color: transparent;
        border-bottom: 2px solid var(--primary);
        font-weight: 600;
    }
    
    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .profile-sidebar {
            position: static;
            margin-bottom: 2rem;
        }
        
        .nav-pills-custom .nav-link {
            padding: 10px 14px;
            font-size: 0.9rem;
        }
        
        .order-item-img {
            width: 60px;
            height: 60px;
        }
        
        .order-actions {
            width: 100%;
            justify-content: stretch;
        }
        
        .order-actions .btn {
            flex: 1;
            min-width: 0;
        }
        
        .nav-tabs .nav-link {
            padding: 10px 12px;
            font-size: 0.85rem;
        }
        
        .order-header-info {
            font-size: 0.9rem;
        }
        
        .total-amount {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .order-item {
            flex-direction: column;
            align-items: flex-start;
            text-align: center;
        }
        
        .order-item-img {
            margin: 0 auto 12px auto;
        }
        
        .order-item-details {
            width: 100%;
            text-align: center;
        }
        
        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .nav-tabs .nav-link {
            white-space: nowrap;
            min-width: 80px;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            @include('customer.profile.sidebar')
        </div>
        
        <div class="col-lg-9">
            <div class="tab-content" id="profileTabContent">
                @include('customer.profile.personal-info')
                @include('customer.profile.addresses')
                @include('customer.profile.orders')
                @include('customer.profile.appointments')
                @include('customer.profile.change-password')
            </div>
        </div>
    </div>
</div>

<!-- Include all modals -->
@include('customer.profile.modals.add-address')
@include('customer.profile.modals.edit-address')
@include('customer.profile.modals.cancel-order')
@include('customer.profile.modals.reschedule-appointment')
@include('customer.profile.modals.cancel-appointment')

@endsection

@section('scripts')
<script>
    // Profile-specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Check URL parameters for automatic tab opening
        const urlParams = new URLSearchParams(window.location.search);
        const buttonParam = urlParams.get('button');
        
        // If button=order parameter exists, activate the orders tab
        if (buttonParam === 'order') {
            // Find and activate the orders tab
            const ordersTab = document.querySelector('#orders-tab');
            const ordersTabPane = document.querySelector('#orders');
            
            if (ordersTab && ordersTabPane) {
                // Use Bootstrap's tab API to properly activate the tab
                const bsTab = new bootstrap.Tab(ordersTab);
                bsTab.show();
                
                // Scroll to the orders section after a short delay
                setTimeout(() => {
                    const ordersSection = document.querySelector('#orders');
                    if (ordersSection) {
                        ordersSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 300);
            }
        }
        
        // Tab switching functionality
        const profileTabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        profileTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                // Handle tab switching logic
                const targetTab = event.target.getAttribute('href');
                
                // Smooth scroll to top of content on mobile
                if (window.innerWidth <= 768) {
                    document.querySelector('.tab-content').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Address management
        const editAddressBtns = document.querySelectorAll('.edit-address-btn');
        editAddressBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Handle edit address modal
            });
        });

        // Order management
        const deleteAddressForms = document.querySelectorAll('.delete-address-form');
        deleteAddressForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this address?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Image lazy loading fallback
        const productImages = document.querySelectorAll('.product-image');
        productImages.forEach(img => {
            img.addEventListener('error', function() {
                this.style.display = 'none';
                const placeholder = this.parentElement;
                placeholder.innerHTML = '<div class="placeholder-image d-flex align-items-center justify-content-center"><i class="bi bi-image text-muted"></i></div>';
            });
        });

        // Enhanced Cancel Order functionality
        const confirmCancelOrderBtn = document.getElementById('confirmCancelOrder');
        if (confirmCancelOrderBtn) {
            confirmCancelOrderBtn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const button = this;
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cancelling...';
                
                // Send AJAX request to cancel the order
                // Send AJAX request to cancel the order
                fetch(`{{ route('customer.orders.cancel', ':orderId') }}`.replace(':orderId', orderId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
                        modal.hide();
                        
                        // Show success toast with proper parameters
                        showToast('success', 'Order Cancelled', 'Order cancelled successfully!');
                        
                        // Redirect to cancelled tab
                        if (data.redirect_url) {
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 1500);
                        } else {
                            // Fallback: Activate the cancelled tab manually
                            setTimeout(() => {
                                const cancelledTab = document.getElementById('cancelled-tab');
                                const cancelledTabPane = document.getElementById('cancelled');
                                
                                if (cancelledTab && cancelledTabPane) {
                                    // Remove active class from all tabs and panes
                                    document.querySelectorAll('#orderTabs .nav-link').forEach(tab => tab.classList.remove('active'));
                                    document.querySelectorAll('#orderTabsContent .tab-pane').forEach(pane => {
                                        pane.classList.remove('show', 'active');
                                    });
                                    
                                    // Activate cancelled tab
                                    cancelledTab.classList.add('active');
                                    cancelledTabPane.classList.add('show', 'active');
                                    
                                    // Update URL to reflect the tab change
                                    const url = new URL(window.location);
                                    url.searchParams.set('orders_tab', 'cancelled');
                                    window.history.pushState({}, '', url);
                                }
                            }, 1500);
                        }
                    } else {
                        showToast('error', 'Cancellation Failed', data.message || 'Failed to cancel order.');
                        // Reset button
                        button.disabled = false;
                        button.innerHTML = '<i class="bi bi-x-circle me-2"></i>Yes, Cancel Order';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred. Please try again.');
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = '<i class="bi bi-x-circle me-2"></i>Yes, Cancel Order';
                });
            });
        }
        
        // Enhanced Cancel Appointment functionality
        const confirmCancelAppointmentBtn = document.getElementById('confirmCancelAppointment');
        if (confirmCancelAppointmentBtn) {
            confirmCancelAppointmentBtn.addEventListener('click', function() {
                const appointmentId = this.getAttribute('data-appointment-id');
                const reason = document.getElementById('cancel_reason') ? document.getElementById('cancel_reason').value : '';
                const button = this;
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Cancelling...';
                
                // Send AJAX request to cancel the appointment
                fetch(`{{ route('customer.appointments.cancel', ':appointmentId') }}`.replace(':appointmentId', appointmentId), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelAppointmentModal'));
                        modal.hide();
                        
                        // Clear the reason field if it exists
                        if (document.getElementById('cancel_reason')) {
                            document.getElementById('cancel_reason').value = '';
                        }
                        
                        // Show success toast
                        showToast('success', 'Appointment cancelled successfully!', 'Your appointment has been cancelled and the time slot is now available for others.');
                        
                        // Redirect to cancelled appointments tab after a short delay
                        setTimeout(() => {
                            // Activate the cancelled appointments tab
                            const cancelledAppointmentsTab = document.getElementById('cancelled-appointments-tab');
                            const cancelledAppointmentsPane = document.getElementById('cancelled-appointments');
                            
                            if (cancelledAppointmentsTab && cancelledAppointmentsPane) {
                                // Remove active class from all appointment tabs and panes
                                document.querySelectorAll('#appointmentTabs .nav-link').forEach(tab => tab.classList.remove('active'));
                                document.querySelectorAll('#appointmentTabsContent .tab-pane').forEach(pane => {
                                    pane.classList.remove('show', 'active');
                                });
                                
                                // Activate cancelled appointments tab
                                cancelledAppointmentsTab.classList.add('active');
                                cancelledAppointmentsPane.classList.add('show', 'active');
                                
                                // Update URL to reflect the tab change
                                const url = new URL(window.location);
                                url.searchParams.set('appointment_tab', 'cancelled');
                                window.history.pushState({}, '', url);
                            }
                        }, 1500);
                    } else {
                        showToast('error', 'Cancellation Failed', data.message || 'Failed to cancel appointment.');
                        // Reset button
                        button.disabled = false;
                        button.innerHTML = '<i class="bi bi-x-circle me-2"></i>Yes, Cancel Appointment';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error', 'An error occurred. Please try again.');
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = '<i class="bi bi-x-circle me-2"></i>Yes, Cancel Appointment';
                });
            });
        }
        
        // Toast notification function
        function showToast(type, title, message) {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <div class="d-flex align-items-center">
                                <i class="bi ${icon} me-2"></i>
                                <div>
                                    <strong>${title}</strong><br>
                                    <small>${message}</small>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 5000
            });
            
            toast.show();
            
            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }
        
        // Global functions for modal handling
        window.openCancelOrderModal = function(orderId) {
            // Set the order ID in the modal button
            document.getElementById('confirmCancelOrder').setAttribute('data-order-id', orderId);
            
            // Open the modal
            const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        };
        
        window.openCancelAppointmentModal = function(appointmentId) {
            // Set the appointment ID in the modal button
            document.getElementById('confirmCancelAppointment').setAttribute('data-appointment-id', appointmentId);
            
            
            // Open the modal
            const modal = new bootstrap.Modal(document.getElementById('cancelAppointmentModal'));
            modal.show();
        };
    });
</script>
@endsection
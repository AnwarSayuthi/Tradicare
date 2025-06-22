@extends('layout')

@section('title', 'Book Appointment - Tradicare')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Side - Appointment Selection -->
                        <div class="col-lg-7 p-4 p-md-5 border-end">
                            <div class="d-flex align-items-center mb-4">
                                <a href="{{ route('customer.services') }}" class="text-decoration-none text-dark me-3">
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                                <h5 class="mb-0 fw-bold">Select a Slot</h5>
                            </div>
                            
                            <form action="{{ route('customer.appointments.store') }}" method="POST" id="appointment-form">
                                @csrf
                                
                                <!-- Service Selection -->
                                <div class="mb-4">
                                    <label for="service_id" class="form-label">Select Service</label>
                                    <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                        <option value="">Choose a service...</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->service_id }}" 
                                                    data-duration="{{ $service->duration_minutes }}"
                                                    data-price="{{ $service->price }}"
                                                    {{ (old('service_id') == $service->service_id || (isset($selectedServiceId) && $selectedServiceId == $service->service_id)) ? 'selected' : '' }}>
                                                {{ $service->service_name }} - {{ $service->duration_minutes }} mins (RM{{ number_format($service->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Date Selection -->
                                <div class="mb-4">
                                    <label class="form-label">Select Date</label>
                                    <input type="hidden" id="selected_date" name="appointment_date" value="{{ $selectedDate ?? '' }}">
                                    
                                    <div class="date-selector">
                                        <div class="month-selector d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" id="prev-month" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-chevron-left"></i>
                                            </button>
                                            <span id="current-month" class="fw-medium">{{ date('F Y') }}</span>
                                            <button type="button" id="next-month" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-chevron-right"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="weekdays-header d-flex mb-2">
                                            <div class="weekday-cell">SUN</div>
                                            <div class="weekday-cell">MON</div>
                                            <div class="weekday-cell">TUE</div>
                                            <div class="weekday-cell">WED</div>
                                            <div class="weekday-cell">THU</div>
                                            <div class="weekday-cell">FRI</div>
                                            <div class="weekday-cell">SAT</div>
                                        </div>
                                        
                                        <div class="week-navigation d-flex justify-content-between align-items-center mb-2">
                                            <button type="button" id="prev-week" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-chevron-left"></i>
                                            </button>
                                            <button type="button" id="next-week" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-chevron-right"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="date-grid" id="date-grid">
                                            <!-- Dates will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Time Selection -->
                                <div class="mb-4">
                                    <label class="form-label">Select Time</label>
                                    <input type="hidden" id="selected_time" name="available_time_id" value="{{ $selectedTime ?? '' }}">
                                    
                                    <div class="time-slots-container">
                                        <div class="morning-slots mb-3">
                                            <h6 class="text-muted mb-2">Morning</h6>
                                            <div class="time-slots-grid">
                                                @if(isset($morningSlots) && count($morningSlots) > 0)
                                                    @foreach($morningSlots as $slot)
                                                        <div class="time-slot-cell {{ $slot['available'] ? '' : 'unavailable' }}" data-time="{{ $slot['value'] }}">
                                                            <div class="time-slot">{{ $slot['time'] }}</div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">No morning slots available</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="afternoon-slots">
                                            <h6 class="text-muted mb-2">Afternoon</h6>
                                            <div class="time-slots-grid">
                                                @if(isset($afternoonSlots) && count($afternoonSlots) > 0)
                                                    @foreach($afternoonSlots as $slot)
                                                        <div class="time-slot-cell {{ $slot['available'] ? '' : 'unavailable' }}" data-time="{{ $slot['value'] }}">
                                                            <div class="time-slot">{{ $slot['time'] }}</div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">No afternoon slots available</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Mobile Number -->
                                <div class="mb-4">
                                    <label for="tel_number" class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control @error('tel_number') is-invalid @enderror" 
                                           id="tel_number" name="tel_number" 
                                           placeholder="Enter your mobile number" 
                                           value="{{ auth()->user()->tel_number ?? old('tel_number') }}" required>
                                    @error('tel_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                        
                        <!-- Right Side - Booking Details -->
                        <div class="col-lg-5 p-4 p-md-5 bg-gradient-dark booking-details-panel">
                            <h5 class="mb-4 fw-bold text-dark">Booking Details</h5>
                            
                            <!-- Service Details -->
                            <div class="mb-4 detail-card animate-fade-in">
                                <h6 class="text-muted mb-3 detail-header"><i class="bi bi-gem me-2"></i>Service Details</h6>
                                <div id="service-details" class="p-3 rounded-3 bg-white shadow-sm">
                                    <p class="text-muted">No service selected yet</p>
                                </div>
                            </div>
                            
                            <!-- Date & Time -->
                            <div class="mb-4 detail-card animate-fade-in" style="animation-delay: 0.1s;">
                                <h6 class="text-muted mb-3 detail-header"><i class="bi bi-calendar-check me-2"></i>Date & Time</h6>
                                <div id="datetime-details" class="p-3 rounded-3 bg-white shadow-sm">
                                    <p class="text-muted">No date and time selected yet</p>
                                </div>
                            </div>
                            
                            <!-- Booked For -->
                            <div class="mb-4 detail-card animate-fade-in" style="animation-delay: 0.2s;">
                                <h6 class="text-muted mb-3 detail-header"><i class="bi bi-person me-2"></i>Booked for</h6>
                                <div class="booked-for-details p-3 rounded-3 bg-white shadow-sm">
                                    <span class="badge px-3 py-2" style="background-color: #3a3a3a;">{{ auth()->user()->name }}</span>
                                </div>
                            </div>
                            
                            <!-- Total Amount -->
                            <div class="mb-4 detail-card animate-fade-in" style="animation-delay: 0.3s;">
                                <h6 class="text-muted mb-3 detail-header"><i class="bi bi-cash-coin me-2"></i>Total Amount</h6>
                                <div class="total-amount p-3 rounded-3 bg-white shadow-sm">
                                    <h4 class="fw-bold text-dark mb-3" id="total-price">RM0.00</h4>
                                    <!-- Updated Proceed Button -->
                                    <button type="button" id="proceed-button" class="btn w-100 btn-lg pulse-animation" style="background-color: #3a3a3a; color: white;" onclick="showPaymentModal()">
                                        <i class="bi bi-credit-card me-2"></i>Proceed to Pay
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Help Section -->
                            <div class="help-section mb-4 p-4 rounded-4 bg-white shadow-sm animate-fade-in" style="animation-delay: 0.4s;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 support-icon-container">
                                        <i class="bi bi-headset support-icon"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">We can help you</h6>
                                        <p class="mb-0 small">Call +60193325968 for chat with our customer support team</p>
                                    </div>
                                </div>
                                <a href="https://wa.me/60193325968" class="btn btn-outline-dark btn-sm mt-3 w-100 hover-scale" target="_blank">
                                    <i class="bi bi-chat-dots me-2"></i>Chat with us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="paymentModalLabel">Complete Your Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Booking Summary -->
                <div class="booking-summary mb-4 p-4 rounded-3" style="background-color: #f8f9fa; border-left: 4px solid #007bff;">
                    <h6 class="fw-bold mb-3">Booking Summary</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted d-block">SERVICE</small>
                                <span id="modal-service-name" class="fw-medium">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">DATE</small>
                                <span id="modal-date" class="fw-medium">-</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted d-block">TIME</small>
                                <span id="modal-time" class="fw-medium">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">DURATION</small>
                                <span id="modal-duration" class="fw-medium">-</span>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Amount:</span>
                        <span class="fw-bold text-primary fs-5" id="modal-total-amount">RM95.00</span>
                    </div>
                </div>
                
                <!-- Payment Method Selection -->
                <div class="payment-methods">
                    <h6 class="fw-bold mb-3">Select Payment Method</h6>
                    <div class="row g-3">
                        <!-- Online Payment -->
                        <div class="col-6">
                            <div class="payment-option border rounded-3 p-4 text-center h-100 position-relative" data-payment="toyyibpay" style="cursor: pointer; transition: all 0.3s ease;">
                                <div class="payment-icon mb-3">
                                    <i class="bi bi-credit-card text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Online Payment</h6>
                                <p class="text-muted small mb-0">Pay securely with Online Banking</p>
                                <div class="payment-check position-absolute top-0 end-0 m-2" style="display: none;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cash Payment -->
                        <div class="col-6">
                            <div class="payment-option border rounded-3 p-4 text-center h-100 position-relative" data-payment="cash" style="cursor: pointer; transition: all 0.3s ease;">
                                <div class="payment-icon mb-3">
                                    <i class="bi bi-cash text-success" style="font-size: 2rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Cash Payment</h6>
                                <p class="text-muted small mb-0">Pay at the Tradicare Center</p>
                                <div class="payment-check position-absolute top-0 end-0 m-2" style="display: none;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-payment-btn" disabled>
                    <i class="bi bi-credit-card me-2"></i>Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const dateGrid = document.getElementById('date-grid');
    const currentMonthEl = document.getElementById('current-month');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const prevWeekBtn = document.getElementById('prev-week');
    const nextWeekBtn = document.getElementById('next-week');
    const selectedDateInput = document.getElementById('selected_date');
    const selectedTimeInput = document.getElementById('selected_time');
    const serviceSelect = document.getElementById('service_id');
    const totalPriceEl = document.getElementById('total-price');
    const serviceDetailsEl = document.getElementById('service-details');
    const datetimeDetailsEl = document.getElementById('datetime-details');
    
    let currentDate = new Date();
    let currentWeekStart = getWeekStart(currentDate);
    let selectedDate = null;
    let selectedTime = null;
    let selectedPaymentMethod = null;
    
    // Initialize
    updateMonthDisplay();
    renderWeek(currentWeekStart);
    updateServiceDetails();
    
    // Initialize with selected date if available 
    if ('{{ isset($selectedDate) }}') { 
        selectedDate = new Date('{{ $selectedDate->format('Y-m-d') }}'); 
        currentDate = new Date('{{ $selectedDate->format('Y-m-d') }}'); 
        currentWeekStart = getWeekStart(currentDate); 
        updateMonthDisplay(); 
        renderWeek(currentWeekStart); 
        
        // Mark the selected date as active after rendering 
        setTimeout(() => { 
            document.querySelectorAll('.date-cell').forEach(cell => { 
                const cellDate = new Date(currentWeekStart); 
                cellDate.setDate(currentWeekStart.getDate() + Array.from(dateGrid.children).indexOf(cell)); 
                
                if (cellDate.toDateString() === selectedDate.toDateString()) { 
                    cell.querySelector('.date-number').classList.add('active'); 
                } 
            }); 
        }, 0); 
    }
    
    // Event Listeners
    prevMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        currentWeekStart = getWeekStart(currentDate);
        updateMonthDisplay();
        renderWeek(currentWeekStart);
    });
    
    nextMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        currentWeekStart = getWeekStart(currentDate);
        updateMonthDisplay();
        renderWeek(currentWeekStart);
    });
    
    prevWeekBtn.addEventListener('click', function() {
        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        renderWeek(currentWeekStart);
    });
    
    nextWeekBtn.addEventListener('click', function() {
        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
        renderWeek(currentWeekStart);
    });
    
    serviceSelect.addEventListener('change', function() {
        updateServiceDetails();
        
        // Refresh time slots if a date is selected
        if (selectedDate) {
            window.location.href = `{{ route('customer.appointments.create') }}?service_id=${this.value}&date=${selectedDate.toISOString().split('T')[0]}`;
        }
    });
    
    // Functions
    function getWeekStart(date) {
        const d = new Date(date);
        const day = d.getDay(); // 0 for Sunday, 1 for Monday, etc.
        const daysFromFriday = (day + 5) % 7; // Calculate days from Friday (5 is Friday, so we add 2 and mod 7)
        d.setDate(d.getDate() - daysFromFriday); // Go to the start of the week (Friday)
        return d;
    }
    
    function updateMonthDisplay() {
        currentMonthEl.textContent = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
    }
    
    function renderWeek(startDate) {
        dateGrid.innerHTML = '';
        
        for (let i = 0; i < 7; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            
            const dateCell = document.createElement('div');
            dateCell.className = 'date-cell';
            
            const dateNumber = document.createElement('div');
            dateNumber.className = 'date-number';
            if (date.toDateString() === new Date().toDateString()) {
                dateNumber.classList.add('today');
            }
            dateNumber.textContent = date.getDate();
            
            // Make dates in the past disabled
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (date < today) {
                dateCell.classList.add('disabled');
            } else {
                dateCell.addEventListener('click', function() {
                    // Remove active class from all date cells
                    document.querySelectorAll('.date-cell .date-number').forEach(el => {
                        el.classList.remove('active');
                    });
                    
                    // Add active class to selected date
                    dateNumber.classList.add('active');
                    
                    // Update selected date
                    selectedDate = date;
                    selectedDateInput.value = date.toISOString().split('T')[0];
                    
                    // Update booking details
                    updateDateTimeDetails();
                    
                    // Refresh the page with the selected date to load new time slots
                    const serviceId = serviceSelect.value;
                    if (serviceId) {
                        window.location.href = `{{ route('customer.appointments.create') }}?service_id=${serviceId}&date=${date.toISOString().split('T')[0]}`;
                    }
                });
            }
            
            dateCell.appendChild(dateNumber);
            dateGrid.appendChild(dateCell);
        }
    }
    
    function updateServiceDetails() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            const serviceName = selectedOption.text.split(' - ')[0];
            const duration = selectedOption.getAttribute('data-duration');
            const price = selectedOption.getAttribute('data-price');
            
            serviceDetailsEl.innerHTML = `
                <div class="selected-service">
                    <h6 class="mb-1">${serviceName}</h6>
                    <p class="mb-0 small text-muted">${duration} minutes</p>
                </div>
            `;
            
            totalPriceEl.textContent = `RM${parseFloat(price).toFixed(2)}`;
        } else {
            serviceDetailsEl.innerHTML = `<p class="text-muted">No service selected yet</p>`;
            totalPriceEl.textContent = 'RM0.00';
        }
    }
    
    function updateDateTimeDetails() {
        if (selectedDate && selectedTime) {
            const formattedDate = selectedDate.toLocaleDateString('en-US', { 
                weekday: 'short', 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
            
            datetimeDetailsEl.innerHTML = `
                <div class="selected-datetime">
                    <p class="mb-1"><i class="bi bi-calendar-date me-2"></i>${formattedDate}</p>
                    <p class="mb-0"><i class="bi bi-clock me-2"></i>${selectedTime}</p>
                </div>
            `;
        } else if (selectedDate) {
            const formattedDate = selectedDate.toLocaleDateString('en-US', { 
                weekday: 'short', 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
            
            datetimeDetailsEl.innerHTML = `
                <div class="selected-datetime">
                    <p class="mb-1"><i class="bi bi-calendar-date me-2"></i>${formattedDate}</p>
                    <p class="mb-0 text-muted">No time selected yet</p>
                </div>
            `;
        } else {
            datetimeDetailsEl.innerHTML = `<p class="text-muted">No date and time selected yet</p>`;
        }
    }
    
    // Time slot selection
    document.querySelectorAll('.time-slot-cell').forEach(slot => {
        slot.addEventListener('click', function() {
            if (this.classList.contains('unavailable')) {
                return; // Don't allow selection of unavailable slots
            }
            
            // Remove active class from all time slots
            document.querySelectorAll('.time-slot-cell').forEach(el => {
                el.classList.remove('active');
            });
            
            // Add active class to selected time slot
            this.classList.add('active');
            
            // Update selected time
            selectedTime = this.querySelector('.time-slot').textContent;
            selectedTimeInput.value = this.getAttribute('data-time'); // This will be the available_time_id
            
            // Update booking details
            updateDateTimeDetails();
        });
    });
    
    // Payment Modal Functions
    window.showPaymentModal = function() {
        // Validate form before showing modal
        if (!validateAppointmentForm()) {
            return;
        }
        
        // Update modal with booking details
        updatePaymentModal();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }
    
    function validateAppointmentForm() {
        const serviceId = document.getElementById('service_id').value;
        const appointmentDate = document.getElementById('selected_date').value;
        const timeId = document.getElementById('selected_time').value;
        const telNumber = document.getElementById('tel_number').value;
        
        if (!serviceId) {
            alert('Please select a service.');
            return false;
        }
        
        if (!appointmentDate) {
            alert('Please select a date.');
            return false;
        }
        
        if (!timeId) {
            alert('Please select a time slot.');
            return false;
        }
        
        if (!telNumber) {
            alert('Please enter your mobile number.');
            return false;
        }
        
        return true;
    }
    
    function updatePaymentModal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const serviceName = selectedOption.text.split(' - ')[0];
        const duration = selectedOption.getAttribute('data-duration');
        const price = selectedOption.getAttribute('data-price');
        
        // Update modal content
        document.getElementById('modal-service-name').textContent = serviceName;
        document.getElementById('modal-duration').textContent = duration + ' minutes';
        document.getElementById('modal-total-amount').textContent = `RM${parseFloat(price).toFixed(2)}`;
        
        if (selectedDate) {
            const formattedDate = selectedDate.toLocaleDateString('en-US', { 
                weekday: 'long',
                day: 'numeric', 
                month: 'long', 
                year: 'numeric' 
            });
            document.getElementById('modal-date').textContent = formattedDate;
        }
        
        if (selectedTime) {
            // Extract time range from selected time
            const timeSlot = selectedTime;
            document.getElementById('modal-time').textContent = timeSlot;
        }
    }
    
    // Payment method selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove active state from all options
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('border-primary', 'bg-light');
                opt.querySelector('.payment-check').style.display = 'none';
            });
            
            // Add active state to selected option
            this.classList.add('border-primary', 'bg-light');
            this.querySelector('.payment-check').style.display = 'block';
            
            // Store selected payment method
            selectedPaymentMethod = this.getAttribute('data-payment');
            
            // Enable confirm button
            document.getElementById('confirm-payment-btn').disabled = false;
        });
    });
    
    // Confirm payment button
    document.getElementById('confirm-payment-btn').addEventListener('click', function() {
        if (!selectedPaymentMethod) {
            alert('Please select a payment method.');
            return;
        }
        
        // Add payment method to form
        const form = document.getElementById('appointment-form');
        const paymentMethodInput = document.createElement('input');
        paymentMethodInput.type = 'hidden';
        paymentMethodInput.name = 'payment_method';
        paymentMethodInput.value = selectedPaymentMethod;
        form.appendChild(paymentMethodInput);
        
        // For cash payment, redirect to a different route that handles cash payments
        if (selectedPaymentMethod === 'cash') {
            form.action = '{{ route("customer.appointments.store.cash") }}';
        }
        
        // Submit the form
        form.submit();
    });
});
</script>

<style>
/* Date Selector Styles */
.date-selector {
    margin-bottom: 1.5rem;
}

.weekdays-header {
    display: flex;
    justify-content: space-between;
}

.weekday-cell {
    flex: 1;
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
}

.date-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: space-between;
}

.date-cell {
    width: calc(100% / 7 - 0.5rem);
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.date-cell.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.date-number {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-weight: 500;
}

.date-number.today {
    border: 2px solid #a8a079;
    color: 	#a8a079;
}

.date-number.active {
    background-color: #a8a079;
    color: white;
}

/* Time Slots Styles */
.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.time-slot-cell {
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
}

.time-slot-cell:hover {
    border-color: #bcb8ad;
}

.time-slot-cell.active {
    background-color: #bcb8ad;
    border-color: #bcb8ad;
    color: white;
}

.time-slot {
    font-size: 0.875rem;
    font-weight: 500;
}

/* Booking Details Panel Styles */
.booking-details-panel {
    background: linear-gradient(145deg,#e9ecef, #a8a079);
    border-radius: 0 1rem 1rem 0;
    position: relative;
    overflow: hidden;
}

.booking-details-panel::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(13, 110, 253, 0.1);
    z-index: 0;
}

.booking-details-panel::after {
    content: '';
    position: absolute;
    bottom: -30px;
    left: -30px;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(13, 110, 253, 0.05);
    z-index: 0;
}

.detail-card {
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
}

.detail-card:hover {
    transform: translateY(-3px);
}

.detail-header {
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
}

/* Animation Styles */
.animate-fade-in {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.pulse-animation {
    animation: pulse 2s infinite;
    box-shadow: rgba(58, 58, 58, 0.4);
    position: relative;
    overflow: hidden;
}

.pulse-animation::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #3a3a3a;
    transform: translateX(-100%);
    animation: shimmer 2.5s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(58, 58, 58, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(58, 58, 58, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(58, 58, 58, 0);
    }
}

@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}

.hover-scale {
    transition: transform 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}

.support-icon-container {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(13, 110, 253, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.support-icon-container:hover {
    background: rgba(13, 110, 253, 0.2);
    transform: rotate(15deg);
}

.support-icon {
    font-size: 1.5rem;
    color: 	#a8a079;
}

/* Card Styles */
.bg-white {
    background-color: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.bg-white:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
}

/* Time slot-cell unavailable */
.time-slot-cell.unavailable {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.time-slot-cell.unavailable:hover {
    border-color: #dee2e6;
    transform: none;
}

/* Total Price Styles */
#total-price {
    font-size: 1.8rem;
    background: linear-gradient(45deg, #3a3a3a, #a8a079);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
}

/* Payment Modal Styles */
.payment-option {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.payment-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.payment-option.border-primary {
    border-color: #007bff !important;
    background-color: #f8f9fa !important;
}

.modal-content {
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.booking-summary {
    border-left: 4px solid #007bff;
}
</style>
@endsection
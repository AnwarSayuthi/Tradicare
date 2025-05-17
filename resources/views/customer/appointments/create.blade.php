@extends('layout')

@section('title', 'Book Appointment - Tradicare')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="h3 fw-bold text-primary-custom">Book Your Appointment</h2>
                        <p class="text-muted">Complete the form below to schedule your wellness session</p>
                    </div>
                    
                    <form action="{{ route('customer.appointments.store') }}" method="POST" id="appointment-form">
                        @csrf
                        
                        @if(isset($selectedService))
                            <input type="hidden" name="service_id" value="{{ $selectedService->service_id }}">
                            <div class="selected-service mb-4">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="flex-shrink-0">
                                        <div class="service-icon-sm">
                                            <i class="bi {{ $selectedService->icon ?? 'bi-gem' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">{{ $selectedService->service_name }}</h5>
                                        <div class="d-flex text-muted small">
                                            <span class="me-3"><i class="bi bi-clock me-1"></i> {{ $selectedService->duration_minutes }} mins</span>
                                            <span><i class="bi bi-tag me-1"></i> RM{{ number_format($selectedService->price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <label for="service_id" class="form-label">Select Service</label>
                                <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                    <option value="">Choose a service...</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->service_id }}" 
                                                data-duration="{{ $service->duration_minutes }}"
                                                data-price="{{ $service->price }}">
                                            {{ $service->service_name }} - {{ $service->duration_minutes }} mins (RM{{ number_format($service->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="appointment_date" class="form-label">Preferred Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="text" class="form-control" id="appointment_date" name="appointment_date" 
                                       value="{{ $selectedDate ?? date('m/d/Y', strtotime('+1 day')) }}"
                                       required readonly>
                                <button class="btn btn-outline-secondary" type="button" id="calendar-btn">
                                    <i class="bi bi-calendar-date"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Available Time Slots -->
                        <div class="mb-4">
                            <label class="form-label">Available Time Slots</label>
                            <div class="time-slots-container" id="time-slots-container">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> Please select a date to view available time slots.
                                </div>
                            </div>
                            @error('appointment_time')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Special Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                    placeholder="Any special requests or health concerns we should know about?"></textarea>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms_agreed" name="terms_agreed" required>
                                <label class="form-check-label" for="terms_agreed">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                                </label>
                                @error('terms_agreed')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-calendar-check me-2"></i> Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Appointment Policy</h6>
                <p>Please arrive 15 minutes before your scheduled appointment time to complete any necessary paperwork and prepare for your treatment.</p>
                
                <h6>Cancellation Policy</h6>
                <p>We understand that schedules change. We ask that you notify us at least 24 hours in advance if you need to cancel or reschedule your appointment to avoid a cancellation fee.</p>
                
                <h6>Late Arrival Policy</h6>
                <p>If you arrive late for your scheduled appointment, your treatment time may be reduced to accommodate other scheduled appointments. Full treatment prices will apply.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-custom" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .service-icon-sm {
        width: 50px;
        height: 50px;
        background: var(--primary-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-icon-sm i {
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    .time-slots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .time-slot-item {
        margin-bottom: 10px;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .calendar-day:hover:not(.disabled) {
        background-color: #f0f0f0;
    }
    
    .calendar-day.today {
        border: 2px solid var(--primary);
    }
    
    .calendar-day.selected {
        background-color: var(--primary);
        color: white;
    }
    
    .calendar-day.disabled {
        color: #ccc;
        cursor: not-allowed;
    }
    
    .calendar-day.other-month {
        color: #aaa;
    }
    
    .modal-time-slot {
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .modal-time-slot:hover {
        background-color: #f8f9fa;
    }
    
    .modal-time-slot.selected {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const appointmentDateInput = document.getElementById('appointment_date');
        const calendarBtn = document.getElementById('calendar-btn');
        const calendarModal = new bootstrap.Modal(document.getElementById('calendarModal'));
        const prevMonthBtn = document.getElementById('prev-month');
        const nextMonthBtn = document.getElementById('next-month');
        const currentMonthEl = document.getElementById('current-month');
        const calendarGrid = document.getElementById('calendar-grid');
        const timeSlotsSection = document.getElementById('time-slots-section');
        const modalTimeSlots = document.getElementById('modal-time-slots');
        const noSlotsMessage = document.getElementById('no-slots-message');
        const selectedDateDisplay = document.getElementById('selected-date-display');
        const continueBtn = document.getElementById('continue-btn');
        const timeSlotsContainer = document.getElementById('time-slots-container');
        
        // Current date and selected date
        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();
        let selectedDate = null;
        let selectedTimeSlot = null;
        
        // Sample time slots (in a real app, these would come from the server)
        const sampleTimeSlots = {
            '2025-05-11': ['11:40 AM', '1:00 PM', '2:00 PM', '2:30 PM', '3:30 PM'],
            '2025-05-12': ['10:00 AM', '11:30 AM', '1:30 PM', '3:00 PM'],
            '2025-05-13': ['9:30 AM', '12:00 PM', '2:30 PM', '4:00 PM'],
            '2025-05-14': ['10:30 AM', '1:00 PM', '3:30 PM'],
            '2025-05-15': ['9:00 AM', '11:00 AM', '2:00 PM', '4:30 PM'],
            '2025-05-16': ['10:00 AM', '12:30 PM', '3:00 PM'],
            '2025-05-17': ['11:00 AM', '1:30 PM', '3:30 PM']
        };
        
        // Open calendar modal when button is clicked
        calendarBtn.addEventListener('click', function() {
            renderCalendar();
            calendarModal.show();
        });
        
        // Navigate to previous month
        prevMonthBtn.addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });
        
        // Navigate to next month
        nextMonthBtn.addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });
        
        // Continue button click handler
        continueBtn.addEventListener('click', function() {
            if (selectedDate && selectedTimeSlot) {
                const formattedDate = formatDate(selectedDate);
                appointmentDateInput.value = formattedDate;
                
                // Update the time slots in the main form
                renderMainFormTimeSlots(selectedDate);
                
                calendarModal.hide();
            }
        });
        
        // Render the calendar for the current month
        function renderCalendar() {
            // Update the month/year display
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            currentMonthEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            // Clear the grid
            calendarGrid.innerHTML = '';
            
            // Get the first day of the month
            const firstDay = new Date(currentYear, currentMonth, 1);
            const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
            
            // Get the number of days in the month
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const totalDays = lastDay.getDate();
            
            // Get the number of days from the previous month to display
            const prevMonthDays = startingDay;
            
            // Get the last day of the previous month
            const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
            
            // Add days from the previous month
            for (let i = prevMonthDays - 1; i >= 0; i--) {
                const day = prevMonthLastDay - i;
                const dayEl = createDayElement(day, true, false);
                calendarGrid.appendChild(dayEl);
            }
            
            // Add days for the current month
            for (let i = 1; i <= totalDays; i++) {
                const date = new Date(currentYear, currentMonth, i);
                const isToday = date.toDateString() === today.toDateString();
                const isSelected = selectedDate && date.toDateString() === selectedDate.toDateString();
                const isPast = date < new Date(today.setHours(0, 0, 0, 0));
                
                const dayEl = createDayElement(i, false, isPast, isToday, isSelected);
                
                // Add click event for valid days
                if (!isPast) {
                    dayEl.addEventListener('click', function() {
                        // Deselect previously selected day
                        const prevSelected = document.querySelector('.calendar-day.selected');
                        if (prevSelected) {
                            prevSelected.classList.remove('selected');
                        }
                        
                        // Select this day
                        dayEl.classList.add('selected');
                        selectedDate = new Date(currentYear, currentMonth, i);
                        
                        // Reset selected time slot
                        selectedTimeSlot = null;
                        continueBtn.disabled = true;
                        
                        // Format date for display
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        selectedDateDisplay.textContent = selectedDate.toLocaleDateString('en-US', options);
                        
                        // Show time slots for this date
                        showTimeSlotsForDate(selectedDate);
                    });
                }
                
                calendarGrid.appendChild(dayEl);
            }
            
            // Add days from the next month if needed to fill the grid
            const totalCells = 42; // 6 rows of 7 days
            const nextMonthDays = totalCells - (prevMonthDays + totalDays);
            
            for (let i = 1; i <= nextMonthDays; i++) {
                const dayEl = createDayElement(i, true, false);
                calendarGrid.appendChild(dayEl);
            }
            
            // Hide time slots section initially
            timeSlotsSection.classList.add('d-none');
            noSlotsMessage.classList.add('d-none');
        }
        
        // Create a day element for the calendar
        function createDayElement(day, isOtherMonth, isDisabled, isToday = false, isSelected = false) {
            const dayEl = document.createElement('div');
            dayEl.classList.add('calendar-day');
            dayEl.textContent = day;
            
            if (isOtherMonth) {
                dayEl.classList.add('other-month');
            }
            
            if (isDisabled) {
                dayEl.classList.add('disabled');
            }
            
            if (isToday) {
                dayEl.classList.add('today');
            }
            
            if (isSelected) {
                dayEl.classList.add('selected');
            }
            
            return dayEl;
        }
        
        // Show time slots for the selected date
        function showTimeSlotsForDate(date) {
            const dateString = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            const availableSlots = sampleTimeSlots[dateString] || [];
            
            if (availableSlots.length > 0) {
                // Show time slots section
                timeSlotsSection.classList.remove('d-none');
                noSlotsMessage.classList.add('d-none');
                
                // Clear previous time slots
                modalTimeSlots.innerHTML = '';
                
                // Add time slots
                availableSlots.forEach(slot => {
                    const slotEl = document.createElement('div');
                    slotEl.classList.add('modal-time-slot');
                    slotEl.textContent = slot;
                    
                    slotEl.addEventListener('click', function() {
                        // Deselect previously selected slot
                        const prevSelected = document.querySelector('.modal-time-slot.selected');
                        if (prevSelected) {
                            prevSelected.classList.remove('selected');
                        }
                        
                        // Select this slot
                        slotEl.classList.add('selected');
                        selectedTimeSlot = slot;
                        
                        // Enable continue button
                        continueBtn.disabled = false;
                    });
                    
                    modalTimeSlots.appendChild(slotEl);
                });
            } else {
                // Show no slots message
                timeSlotsSection.classList.add('d-none');
                noSlotsMessage.classList.remove('d-none');
            }
        }
        
        // Render time slots in the main form
        function renderMainFormTimeSlots(date) {
            const dateString = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            const availableSlots = sampleTimeSlots[dateString] || [];
            
            // Clear previous time slots
            timeSlotsContainer.innerHTML = '';
            
            if (availableSlots.length > 0) {
                const timeSlotsGrid = document.createElement('div');
                timeSlotsGrid.classList.add('time-slots-grid');
                
                availableSlots.forEach((slot, index) => {
                    const timeSlotItem = document.createElement('div');
                    timeSlotItem.classList.add('time-slot-item');
                    
                    const isSelected = slot === selectedTimeSlot;
                    
                    timeSlotItem.innerHTML = `
                        <input type="radio" class="btn-check" name="appointment_time" 
                               id="time-${index}" value="${slot}" 
                               autocomplete="off" required ${isSelected ? 'checked' : ''}>
                        <label class="btn btn-outline-secondary w-100" for="time-${index}">
                            ${slot}
                        </label>
                    `;
                    
                    timeSlotsGrid.appendChild(timeSlotItem);
                });
                
                timeSlotsContainer.appendChild(timeSlotsGrid);
            } else {
                timeSlotsContainer.innerHTML = `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i> No available slots for the selected date. Please try another date.
                    </div>
                `;
            }
        }
        
        // Format date as MM/DD/YYYY
        function formatDate(date) {
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            return `${month}/${day}/${year}`;
        }
    });
</script>
@endsection
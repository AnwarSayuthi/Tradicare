<!-- Reschedule Appointment Modal -->
<div class="modal fade" id="rescheduleAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reschedule Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rescheduleAppointmentForm" method="POST">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="reschedule_date" class="form-label">New Date</label>
                        <input type="date" class="form-control" id="reschedule_date" name="appointment_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reschedule_time" class="form-label">New Time</label>
                        <select class="form-control" id="reschedule_time" name="available_time_id" required>
                            <option value="">Select Time</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reschedule_reason" class="form-label">Reason for Rescheduling</label>
                        <textarea class="form-control" id="reschedule_reason" name="reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-calendar-check me-2"></i>Reschedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
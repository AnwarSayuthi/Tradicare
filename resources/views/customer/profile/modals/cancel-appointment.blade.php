<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this appointment?</p>
                <p class="text-muted small">This action cannot be undone.</p>
                
                <div class="mb-3">
                    <label for="cancel_reason" class="form-label">Reason for Cancellation</label>
                    <textarea class="form-control" id="cancel_reason" name="reason" rows="3" placeholder="Please provide a reason for cancellation..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Appointment</button>
                <button type="button" class="btn btn-danger" id="confirmCancelAppointment">Yes, Cancel Appointment</button>
            </div>
        </div>
    </div>
</div>
<!-- Empty Appointments State -->
<div class="text-center py-5">
    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
    <h5 class="mt-3">No Appointments Found</h5>
    <p class="text-muted">You haven't booked any appointments yet.</p>
    <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary mt-2">
        <i class="bi bi-plus-circle me-2"></i>Book Your First Appointment
    </a>
</div>
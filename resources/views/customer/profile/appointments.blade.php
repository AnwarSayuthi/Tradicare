<div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">My Appointments</h5>
        </div>
        <div class="card-body">
            @if($appointments->isEmpty())
                @include('customer.profile.partials.empty-appointments')
            @else
                @include('customer.profile.partials.appointment-list')
            @endif
        </div>
    </div>
</div>
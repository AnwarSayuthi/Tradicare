<div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
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
<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Change Password</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('customer.profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>
                
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-shield-check me-2"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>
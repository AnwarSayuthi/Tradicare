<div class="profile-sidebar card border-0 shadow-sm">
    <div class="card-body text-center">
        <div class="profile-avatar mb-3">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile" class="rounded-circle avatar-img">
            @else
                <div class="avatar-placeholder">
                    <i class="bi bi-person"></i>
                </div>
            @endif
        </div>
        <h5 class="profile-name mb-1">{{ auth()->user()->name }}</h5>
        <p class="profile-email text-muted mb-4">{{ auth()->user()->email }}</p>
        
        <div class="profile-nav">
            <ul class="nav flex-column nav-pills-custom">
                <li class="nav-item">
                    <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">
                        <i class="bi bi-person me-2"></i> 
                        <span>Personal Info</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="addresses-tab" data-bs-toggle="tab" href="#addresses" role="tab">
                        <i class="bi bi-geo-alt me-2"></i> 
                        <span>My Addresses</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                        <i class="bi bi-bag me-2"></i> 
                        <span>My Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="appointments-tab" data-bs-toggle="tab" href="#appointments" role="tab">
                        <i class="bi bi-calendar-check me-2"></i> 
                        <span>My Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                        <i class="bi bi-shield-lock me-2"></i> 
                        <span>Change Password</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<li class="nav-item">
    <a class="nav-link px-3" href="{{ route('customer.appointments.index') }}">
        <i class="bi bi-calendar-check me-2"></i> My Appointments
    </a>
</li>
<li class="nav-item">
    <a class="nav-link px-3" href="{{ route('customer.products.index') }}">
        <i class="bi bi-shop me-2"></i> Shop
    </a>
</li>
<li class="nav-item">
    <a class="nav-link px-3 position-relative" href="{{ route('customer.cart') }}">
        <i class="bi bi-cart me-2"></i> Cart 
        @if(Auth::check() && Auth::user()->cart()->count() > 0)
            <span class="badge rounded-pill bg-primary position-absolute top-0 start-100 translate-middle">
                {{ Auth::user()->cart()->count() }}
            </span>
        @endif
    </a>
</li>
<li class="nav-item">
    <a class="nav-link px-3" href="{{ route('customer.orders') }}">
        <i class="bi bi-bag me-2"></i> My Orders
    </a>
</li>
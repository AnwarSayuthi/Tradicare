<li class="nav-item">
    <a class="nav-link px-3" href="{{ route('customer.services') }}">
        <i class="bi bi-calendar-plus me-2"></i> Make Appointment
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
                {{-- {{ Auth::user()->cart()->where('status', 'active')->first()?->cartItems()->count() ?? 0 }} --}}
                {{ Auth::user()->activeCartItemsCount() }}
            </span>
        @endif
    </a>
</li>
<li class="nav-item">
    <a class="nav-link px-3" href="{{ route('customer.about') }}">
        <i class="bi bi-info-circle me-2"></i> About Us
    </a>
</li>
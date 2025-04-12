<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container-lg container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{asset('/image/logo.png')}}" alt="Tradicare" height="50" class="me-2">
            <span class="brand-text d-none d-sm-inline">Tradicare</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if(Auth::user()->role === 'customer')
                        @include('customer.nav')
                    @endif
                @endauth
            </ul>
            
            <ul class="navbar-nav align-items-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link hover-effect px-3" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary-custom ms-2" href="{{ route('register') }}">
                            Register
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar me-2">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animate slideIn">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('customer.profile') }}">
                                    <i class="bi bi-person me-2"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
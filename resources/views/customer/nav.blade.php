<!DOCTYPE html>
<html>
<head>
    <title>@yield("title", "Tradicare - Customer Pages")</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @yield('css')
    <style>
        @import url('https://fonts.googleapis.com/css?family=Raleway:300,400,600');
  
        body {
            margin: 0;
            font-size: .9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #000000;
            text-align: left;
            background-color: #0f4320;
        }
        .navbar {
            background-color: #f3edc6 !important; /* Updated background color */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-nav .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #000; /* Indicator for the active page */
            color: #000 !important;
        }
        .navbar-brand, .nav-link {
            font-family: Raleway, sans-serif;
        }
        .hero {
            background-color: #f3edc6;
            padding: 3rem;
            text-align: center;
        }
        .hero h1 {
            font-weight: 700;
        }
        .hero p.lead {
            font-size: 1.2rem;
            color: #000000;
        }
        .discover-section {
            text-align: center;
            margin-top: 2rem;
        }
        .discover-section h2 {
            font-weight: bold;
        }
        footer {
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            background-color: #f3edc6;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    
<div id="customer.nav">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                Tradicare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('customer/homepage') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('products') ? 'active' : '' }}" href="{{ url('customer/products') }}">Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('appointments') ? 'active' : '' }}" href="{{ url('customer/appointments') }}">Make Appointment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('customer/about') }}">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('customer/contact') }}">Contact</a>
                        </li>
                    
                        <!-- Additional Icons -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" title="Notifications">
                                <i class="bi bi-bell"></i> <!-- Notification Icon -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('cart') }}" title="Cart">
                                <i class="bi bi-cart"></i> <!-- Cart Icon -->
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> <!-- User Icon -->
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ url('profile') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ url('manageAppointment') }}">Manage Appointment</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>
</div>

<!-- Footer -->
<footer>
    Made with ♥ by Khairul Anwar
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
@yield('js')
</body>
</html>


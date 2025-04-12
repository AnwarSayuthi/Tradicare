<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Tradicare - Traditional Herbal Medicine and Wellness">
    <meta name="keywords" content="herbal, traditional medicine, wellness, health, natural remedies">
    <meta name="author" content="Tradicare">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('image/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Tradicare')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @include('components.styles')
    <style>
        /* Variables */
        :root {
            --primary: #493628;
            --primary-light: #D6C0B3;
            --secondary: #8B7355;
            --gradient-primary: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }
        
        /* Base Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FA;
        }
        
        /* Navigation Styles */
        .navbar {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--primary) !important;
        }
        
        .brand-text {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .nav-link {
            color: var(--primary) !important;
            font-weight: 500;
            letter-spacing: 0.3px;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--secondary) !important;
        }
        
        /* Button Styles */
        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-effect {
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(73, 54, 40, 0.15);
        }
        
        .btn-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(73, 54, 40, 0.2);
        }
        
        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.07);
            border-radius: 12px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            border-radius: 8px;
            margin: 2px 0;
        }
        
        /* Animation Styles */
        .animate {
            animation-duration: 0.3s;
            animation-fill-mode: both;
        }
        
        @keyframes slideIn {
            0% {
                transform: translateY(1rem);
                opacity: 0;
            }
            100% {
                transform: translateY(0rem);
                opacity: 1;
            }
        }
        
        .slideIn {
            animation-name: slideIn;
        }
        
        .hover-effect:hover {
            color: var(--primary) !important;
            transform: translateY(-1px);
        }
        
        /* Container Styles */
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }
        
        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 10px;
        }
        
        /* Card Styles */
        .card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        /* Section Padding */
        .section-padding {
            padding: 5rem 0;
        }
        
        /* Responsive Styles */
        @media (max-width: 991.98px) {
            .section-padding {
                padding: 3rem 0;
            }
        }
        
        @media (max-width: 767.98px) {
            .section-padding {
                padding: 2rem 0;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }
        
        /* Fade-in animations */
        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @yield('css')
</head>
<body>
    <div id="app">
        @if(!request()->is('login') && !request()->is('register'))
            @include('components.ui.navigation')
        @endif
        
        <main>
            @if(!request()->is('login') && !request()->is('register'))
                <div class="container-lg container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            @endif
            
            @yield('content')
        </main>
        
        <!-- Footer can be added here -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
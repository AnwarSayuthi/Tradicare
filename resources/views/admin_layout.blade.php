<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Tradicare - Traditional Herbal Medicine and Wellness">
    <meta name="keywords" content="herbal, traditional medicine, wellness, health, natural remedies">
    <meta name="author" content="Tradicare">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('image/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Tradicare Admin')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- PDF Generation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <!-- Chart.js and Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/patternomaly@1.3.2/dist/patternomaly.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-gradient@0.6.1/dist/chartjs-plugin-gradient.min.js"></script>
    
    @include('components.styles')
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            /* Primary Theme Colors - Updated to match appointments theme */
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --primary-light: #74b9ff;
            --secondary: #492072;
            --secondary-light: #8b5fbf;
            
            /* Legacy Colors for Compatibility */
            --legacy-primary: #493628;
            --legacy-primary-light: #D6C0B3;
            --legacy-secondary: #8B7355;
            
            /* Modern Gradient Themes */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            --gradient-success: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            --gradient-warning: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
            --gradient-danger: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
            --gradient-light: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            
            /* Layout Variables */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 65px;
            
            /* Shadow Variables */
            --shadow-sm: 0 2px 8px rgba(102, 126, 234, 0.08);
            --shadow-md: 0 4px 20px rgba(102, 126, 234, 0.12);
            --shadow-lg: 0 8px 35px rgba(102, 126, 234, 0.15);
            --shadow-hover: 0 6px 25px rgba(102, 126, 234, 0.25);
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            
            /* Transitions */
            --transition-fast: all 0.2s ease;
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
        }

        /* ===== BASE STYLES ===== */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            overflow-x: hidden;
            line-height: 1.6;
            color: #2d3748;
        }

        /* ===== ADMIN LAYOUT CONTAINER ===== */
        .admin-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ===== ENHANCED SIDEBAR STYLES ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--gradient-primary);
            color: #fff;
            transition: var(--transition-normal);
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .sidebar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            background: linear-gradient(45deg, #fff, #f8f9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-toggle {
            cursor: pointer;
            color: #fff;
            font-size: 1.3rem;
            padding: 8px;
            border-radius: var(--radius-sm);
            transition: var(--transition-fast);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .sidebar-menu {
            padding: 20px 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 8px;
            padding: 0 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 14px 15px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: var(--transition-normal);
            border-radius: var(--radius-md);
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: var(--transition-normal);
        }

        .sidebar-link:hover::before {
            left: 100%;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: inset 3px 0 0 #fff;
        }

        .sidebar-icon {
            margin-right: 12px;
            font-size: 1.3rem;
            min-width: 28px;
            text-align: center;
            transition: var(--transition-fast);
        }

        .sidebar-link:hover .sidebar-icon {
            transform: scale(1.1);
        }

        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
            font-weight: 500;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .sidebar-brand {
            display: none;
        }

        /* ===== ENHANCED MAIN CONTENT STYLES ===== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition-normal);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ===== ENHANCED TOPBAR STYLES ===== */
        .topbar {
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .topbar-title {
            font-weight: 600;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-icon {
            font-size: 1.3rem;
            color: var(--primary);
            cursor: pointer;
            transition: var(--transition-fast);
            padding: 8px;
            border-radius: var(--radius-sm);
            background: rgba(102, 126, 234, 0.05);
        }

        .topbar-icon:hover {
            color: var(--primary-dark);
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.1);
        }

        /* ===== ENHANCED CONTENT AREA ===== */
        .content-area {
            padding: 25px;
            flex: 1;
            background: transparent;
        }

        /* ===== ENHANCED CARD STYLES ===== */
        .card {
            border: none;
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius-lg);
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: var(--transition-normal);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--gradient-light);
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            padding: 20px 25px;
            font-weight: 600;
            color: var(--primary);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .card-body {
            padding: 25px;
        }

        .card-footer {
            background: var(--gradient-light);
            border-top: 1px solid rgba(102, 126, 234, 0.1);
            padding: 15px 25px;
        }

        /* ===== ENHANCED ALERT STYLES ===== */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            margin-bottom: 25px;
            padding: 15px 20px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: var(--gradient-success);
            color: white;
        }

        .alert-danger {
            background: var(--gradient-danger);
            color: white;
        }

        .alert-warning {
            background: var(--gradient-warning);
            color: white;
        }

        .alert-info {
            background: var(--gradient-secondary);
            color: white;
        }

        /* ===== ENHANCED FORM STYLES ===== */
        .form-control, .form-select {
            border-radius: var(--radius-md);
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            transition: var(--transition-normal);
            font-weight: 500;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
            background: white;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-select:focus ~ label {
            color: var(--primary);
        }

        /* ===== ENHANCED BUTTON STYLES ===== */
        .btn {
            border-radius: var(--radius-md);
            padding: 12px 24px;
            font-weight: 600;
            transition: var(--transition-normal);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--gradient-primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: white;
        }

        .btn-secondary {
            background: var(--gradient-secondary);
            color: white;
        }

        .btn-success {
            background: var(--gradient-success);
            color: white;
        }

        .btn-warning {
            background: var(--gradient-warning);
            color: white;
        }

        .btn-danger {
            background: var(--gradient-danger);
            color: white;
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            transition: var(--transition-normal);
            border: 2px solid #e2e8f0;
            background: white;
            font-weight: 500;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        /* ===== ENHANCED TABLE STYLES ===== */
        .table-responsive {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: var(--gradient-primary);
            color: white;
        }

        .table thead th {
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 16px 12px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table tbody tr {
            transition: var(--transition-fast);
            border-bottom: 1px solid #f1f3f4;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }

        .table td {
            padding: 16px 12px;
            vertical-align: middle;
            border-top: none;
        }

        /* ===== ENHANCED PAGINATION STYLES ===== */
        .pagination {
            margin-bottom: 0;
            gap: 5px;
        }

        .pagination .page-link {
            border-radius: var(--radius-md);
            border: 2px solid #e2e8f0;
            color: var(--primary);
            font-weight: 500;
            padding: 12px 16px;
            transition: var(--transition-normal);
            background: white;
        }

        .pagination .page-link:hover {
            background: rgba(102, 126, 234, 0.1);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
            color: var(--primary-dark);
        }

        .pagination .page-item.active .page-link {
            background: var(--gradient-primary);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #cbd5e0;
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        /* ===== STATUS BADGE STYLES ===== */
        .status-badge {
            padding: 8px 16px;
            border-radius: var(--radius-xl);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 2px solid transparent;
            display: inline-block;
            transition: var(--transition-normal);
        }

        .status-badge.active {
            background: var(--gradient-success);
            color: white;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }

        .badge.bg-pending { background: var(--gradient-warning) !important; }
        .badge.bg-processing { background: var(--gradient-secondary) !important; }
        .badge.bg-shipped { background: var(--gradient-primary) !important; }
        .badge.bg-delivered, .badge.bg-completed { background: var(--gradient-success) !important; }
        .badge.bg-cancelled { background: var(--gradient-danger) !important; }
        .badge.bg-refunded { background: linear-gradient(135deg, #6c757d, #495057) !important; }

        /* ===== SERVICE CARD STYLES ===== */
        .service-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-normal);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .service-stats {
            background: var(--gradient-primary);
            padding: 1.5rem;
            border-radius: var(--radius-md);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        /* ===== MOBILE RESPONSIVE STYLES ===== */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed-width);
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-visible {
                transform: translateX(0);
                width: var(--sidebar-width);
            }
            
            .sidebar.mobile-visible .sidebar-text,
            .sidebar.mobile-visible .sidebar-brand {
                display: inline-block;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
            
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
                backdrop-filter: blur(5px);
            }
            
            .mobile-overlay.active {
                display: block;
            }
            
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 45px;
                height: 45px;
                background: var(--gradient-primary);
                color: #fff;
                border-radius: var(--radius-md);
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1050;
                cursor: pointer;
                box-shadow: var(--shadow-md);
                transition: var(--transition-normal);
                border: none;
                opacity: 1;
            }
            
            .mobile-toggle.hidden {
                opacity: 0;
                pointer-events: none;
            }
            
            .mobile-toggle:hover {
                transform: scale(1.1);
                box-shadow: var(--shadow-lg);
            }
            
            .content-area {
                padding-top: 80px;
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .service-card {
                margin-bottom: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .service-stats {
                margin-top: 1rem;
            }
        }

        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 8px 12px;
                font-size: 0.875rem;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 0.875rem;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .content-area {
                padding: 15px;
                padding-top: 80px;
            }
        }

        /* ===== LOADING STATES ===== */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* ===== UTILITY CLASSES ===== */
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background: var(--gradient-primary) !important; }
        .border-primary { border-color: var(--primary) !important; }
        
        .shadow-sm { box-shadow: var(--shadow-sm) !important; }
        .shadow-md { box-shadow: var(--shadow-md) !important; }
        .shadow-lg { box-shadow: var(--shadow-lg) !important; }
        
        .rounded-sm { border-radius: var(--radius-sm) !important; }
        .rounded-md { border-radius: var(--radius-md) !important; }
        .rounded-lg { border-radius: var(--radius-lg) !important; }
        .rounded-xl { border-radius: var(--radius-xl) !important; }

        /* ===== ACCESSIBILITY IMPROVEMENTS ===== */
        .btn:focus,
        .form-control:focus,
        .form-select:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* ===== PERFORMANCE OPTIMIZATIONS ===== */
        .card,
        .btn,
        .sidebar-link {
            will-change: transform;
        }

        .sidebar {
            contain: layout style paint;
        }

        .main-content {
            contain: layout style;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="admin-container">
        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle d-md-none" id="mobile-toggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobile-overlay"></div>
        
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">Tradicare Admin</a>
                <div class="sidebar-toggle" id="sidebar-toggle">
                    <i class="bi bi-list"></i>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 sidebar-icon"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check sidebar-icon"></i>
                        <span class="sidebar-text">Orders</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam sidebar-icon"></i>
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.appointments.index') }}" class="sidebar-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check sidebar-icon"></i>
                        <span class="sidebar-text">Appointments</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                        <i class="bi bi-hand-index sidebar-icon"></i>
                        <span class="sidebar-text">Services</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a href="#" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right sidebar-icon"></i>
                            <span class="sidebar-text">Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center animate__animated animate__fadeInDown">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center animate__animated animate__fadeInDown">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileToggle = document.getElementById('mobile-toggle');
            const mobileOverlay = document.getElementById('mobile-overlay');
            let isButtonVisible = true;
            
            // Enhanced mobile toggle with ripple effect
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                    
                    // Toggle sidebar
                    sidebar.classList.toggle('mobile-visible');
                    mobileOverlay.classList.toggle('active');
                    
                    isButtonVisible = !isButtonVisible;
                    mobileToggle.classList.toggle('hidden', !isButtonVisible);
                });
            }
            
            // Enhanced overlay click with fade effect
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-visible');
                mobileOverlay.classList.remove('active');
                mobileToggle.classList.remove('hidden');
                isButtonVisible = true;
            });
            
            // Enhanced window resize handler
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    mobileOverlay.classList.remove('active');
                    sidebar.classList.remove('mobile-visible');
                    mobileToggle.classList.remove('hidden');
                    isButtonVisible = true;
                }
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
            
            // Enhanced loading states for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.type === 'submit' || this.closest('form')) {
                        this.classList.add('loading');
                        this.disabled = true;
                        
                        setTimeout(() => {
                            this.classList.remove('loading');
                            this.disabled = false;
                        }, 2000);
                    }
                });
            });
        });
        
        // Enhanced status color helper
        function getStatusColor(status) {
            status = status.toLowerCase();
            switch(status) {
                case 'pending': return 'warning';
                case 'processing': return 'info';
                case 'shipped': return 'primary';
                case 'delivered': case 'completed': return 'success';
                case 'cancelled': return 'danger';
                case 'refunded': return 'secondary';
                default: return 'secondary';
            }
        }
        
        // Add ripple effect CSS
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(rippleStyle);
    </script>
    @yield('scripts')
</body>
</html>

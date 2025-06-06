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
    <title>@yield('title', 'Tradicare Admin')</title>
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
    --sidebar-width: 250px;
    --sidebar-collapsed-width: 70px;
    --topbar-height: 60px;
}

/* Base Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #F8F9FA;
    overflow-x: hidden;
}

/* Admin Layout Specific Styles */
.admin-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: var(--sidebar-width);
    background-color: var(--primary);
    color: #fff;
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar-header {
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    height: var(--topbar-height);
}

.sidebar-brand {
    font-weight: 600;
    font-size: 1.2rem;
    color: #fff;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
}

.sidebar-toggle {
    cursor: pointer;
    color: #fff;
    font-size: 1.2rem;
}

.sidebar-menu {
    padding: 15px 0;
    list-style: none;
    margin: 0;
}

.sidebar-item {
    margin-bottom: 5px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.sidebar-link:hover, .sidebar-link.active {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    border-left-color: var(--primary-light);
}

.sidebar-icon {
    margin-right: 10px;
    font-size: 1.2rem;
    min-width: 25px;
    text-align: center;
}

.sidebar-text {
    white-space: nowrap;
    overflow: hidden;
}

.sidebar.collapsed .sidebar-text {
    display: none;
}

.sidebar.collapsed .sidebar-brand {
    display: none;
}

/* Main Content Styles */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    transition: all 0.3s ease;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.main-content.expanded {
    margin-left: var(--sidebar-collapsed-width);
}

/* Topbar Styles */
.topbar {
    height: var(--topbar-height);
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    position: sticky;
    top: 0;
    z-index: 999;
}

.topbar-title {
    font-weight: 600;
    color: var(--primary);
}

.topbar-actions {
    display: flex;
    align-items: center;
}

.topbar-icon {
    font-size: 1.2rem;
    color: var(--primary);
    margin-left: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.topbar-icon:hover {
    color: var(--secondary);
}

/* Content Area */
.content-area {
    padding: 20px;
    flex: 1;
}

/* Card Styles */
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    margin-bottom: 20px;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 15px 20px;
    font-weight: 600;
}

/* Responsive Styles */
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
    }
    
    .mobile-overlay.active {
        display: block;
    }
    
    /* Mobile toggle button styles */
    .mobile-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: var(--primary);
        color: #fff;
        border-radius: 8px;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1050;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        border: none;
        opacity: 1;
    }
    
    .mobile-toggle.hidden {
        opacity: 0;
        pointer-events: none;
    }
    
    .mobile-toggle {
        display: flex;
    }
    
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.mobile-visible {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .content-area {
        padding-top: 70px; /* Add space for the fixed mobile toggle button */
    }
}

/* Alert Styles */
.alert {
    border: none;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* Service Pages Shared Styles */
.service-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.service-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, var(--primary-light), var(--primary));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.service-stats {
    background: linear-gradient(to right, var(--primary-light), var(--primary));
    padding: 1.5rem;
    border-radius: 12px;
    color: #fff;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.875rem;
}

.status-badge.active {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.status-badge.inactive {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

/* Modern Form Styles */
.form-control, .form-select {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.25rem rgba(73, 54, 40, 0.1);
}

/* Custom Button Styles */
.btn-action {
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
}

/* Responsive Table */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
}

/* Pagination Styling */
.pagination {
    margin-bottom: 0;
}

.page-item.active .page-link {
    background-color: var(--primary);
    border-color: var(--primary);
}

.page-link {
    color: var(--primary);
    border-radius: 0.25rem;
    margin: 0 2px;
}

.page-link:hover {
    color: var(--primary-light);
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.page-item:first-child .page-link,
.page-item:last-child .page-link {
    border-radius: 0.25rem;
}

@media (max-width: 576px) {
    .pagination .page-link {
        padding: 0.375rem 0.5rem;
    }
}

@media (max-width: 768px) {
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

/* Status Badge Colors */
.badge.bg-pending { background-color: #ffc107; }
.badge.bg-processing { background-color: #0dcaf0; }
.badge.bg-shipped { background-color: #493628; }
.badge.bg-delivered { background-color: #198754; }
.badge.bg-completed { background-color: #198754; }
.badge.bg-cancelled { background-color: #dc3545; }
.badge.bg-refunded { background-color: #6c757d; }
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
            
            // Mobile toggle button click handler
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-visible');
                    mobileOverlay.classList.toggle('active');
                    
                    // Toggle button visibility
                    isButtonVisible = !isButtonVisible;
                    mobileToggle.classList.toggle('hidden', !isButtonVisible);
                });
            }
            
            // Show button again when overlay is clicked
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-visible');
                mobileOverlay.classList.remove('active');
                mobileToggle.classList.remove('hidden');
                isButtonVisible = true;
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    mobileOverlay.classList.remove('active');
                    sidebar.classList.remove('mobile-visible');
                    mobileToggle.classList.remove('hidden');
                    isButtonVisible = true;
                }
            });
        });
        
        // Helper function for status colors
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
    </script>
    @yield('scripts')
</body>
</html>
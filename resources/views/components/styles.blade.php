<style>
    :root {
        --primary: #493628;
        --primary-light: #D6C0B3;
        --secondary: #8B7355;
        --gradient-primary: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #FAFAFA;
        overflow-x: hidden;
    }

    /* Responsive Typography */
    h1, .h1 { font-size: calc(1.5rem + 1.5vw); }
    h2, .h2 { font-size: calc(1.3rem + 1vw); }
    h3, .h3 { font-size: calc(1.1rem + 0.6vw); }
    
    /* Enhanced Buttons */
    .btn-primary-custom {
        background: var(--gradient-primary);
        border: none;
        color: white;
        padding: 0.8rem 2rem;
        border-radius: 12px;
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(73, 54, 40, 0.15);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-primary-custom:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: all 0.6s ease;
        z-index: -1;
    }

    .btn-primary-custom:hover:before {
        left: 100%;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(73, 54, 40, 0.2);
        color: white;
    }

    /* Improved Form Controls */
    .form-control {
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        border: 1px solid rgba(73, 54, 40, 0.1);
        background-color: rgba(73, 54, 40, 0.02);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(73, 54, 40, 0.1);
        border-color: var(--primary);
        background-color: rgba(73, 54, 40, 0.05);
    }

    /* Enhanced Cards */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Section Styling */
    .section-title {
        font-size: calc(1.8rem + 0.6vw);
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--primary-light);
    }

    .section-subtitle {
        color: var(--secondary);
        font-size: calc(1rem + 0.2vw);
        margin-bottom: 3rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Luxury Animations */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
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

    /* Responsive Containers */
    .container {
        padding-left: max(15px, 4vw);
        padding-right: max(15px, 4vw);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-light);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary);
    }

    /* Responsive Spacing */
    .section-padding {
        padding: calc(3rem + 2vw) 0;
    }

    /* Image Styling */
    .img-cover {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    /* Responsive Navigation */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-top: 1rem;
        }
    }

    /* Responsive Cards */
    @media (max-width: 767.98px) {
        .card {
            margin-bottom: 1.5rem;
        }
    }
    
    /* Enhanced Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin: 2rem 0;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 12px;
        font-weight: 500;
        color: var(--primary);
        background-color: #fff;
        border: 1px solid rgba(73, 54, 40, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .pagination .page-item .page-link:hover {
        background-color: rgba(214, 192, 179, 0.2);
        border-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(73, 54, 40, 0.1);
    }
    
    .pagination .page-item.active .page-link {
        background: var(--gradient-primary);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(73, 54, 40, 0.2);
    }
    
    .pagination .page-item.disabled .page-link {
        color: rgba(73, 54, 40, 0.4);
        pointer-events: none;
        background-color: rgba(73, 54, 40, 0.05);
        border-color: transparent;
    }
    
    /* Pagination navigation arrows */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 1.2rem;
        padding: 0.5rem;
    }
    
    /* Responsive pagination for smaller screens */
    @media (max-width: 576px) {
        .pagination {
            gap: 0.25rem;
        }
        
        .pagination .page-item .page-link {
            min-width: 2rem;
            height: 2rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Hide some page numbers on very small screens */
        .pagination .page-item:not(:first-child):not(:last-child):not(.active):not(.disabled) {
            display: none;
        }
        
        /* But always show the active page and immediate siblings */
        .pagination .page-item.active + .page-item,
        .pagination .page-item.active + .page-item + .page-item,
        .pagination .page-item:nth-last-child(3):not(.active) {
            display: block;
        }
    }

    /* Pagination info text */
    .pagination-info {
        text-align: center;
        color: var(--secondary);
        font-size: 0.9rem;
        margin-top: -1rem;
        margin-bottom: 1rem;
    }
    
    /* Arrow icons */
    .pagination .page-link i.bi {
        font-size: 1.25rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 575.98px) {
        .pagination {
            flex-wrap: wrap;
        }
        
        .pagination .page-item .page-link {
            min-width: 35px;
            height: 35px;
            padding: 0.4rem 0.6rem;
            margin: 0 0.15rem;
            font-size: 0.9rem;
        }
    }

    /* Admin Responsive Styles */
    @media (max-width: 767.98px) {
        .admin-container {
            display: flex;
            min-height: 100vh;
            background-color: var(--admin-bg);
            position: relative;
        }

        /* Admin Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--admin-primary);
            color: white;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: var(--admin-transition);
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .admin-sidebar-brand {
            padding: 1rem;
            display: flex;
            align-items: center;
            height: var(--header-height);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar-brand h1 {
            font-size: 1.25rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: var(--admin-transition);
        }

        .admin-sidebar.collapsed .admin-sidebar-brand h1 {
            opacity: 0;
            width: 0;
        }

        .admin-sidebar-nav {
            padding: 1rem 0;
        }

        .admin-sidebar-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--admin-transition);
            border-left: 3px solid transparent;
        }

        .admin-sidebar-item:hover, 
        .admin-sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--admin-light);
        }

        .admin-sidebar-icon {
            font-size: 1.25rem;
            min-width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-sidebar-text {
            margin-left: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            transition: var(--admin-transition);
        }

        .admin-sidebar.collapsed .admin-sidebar-text {
            opacity: 0;
            width: 0;
        }

        /* Admin Content */
        .admin-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--admin-transition);
            padding: 1rem;
        }

        .admin-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Admin Header */
        .admin-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            background-color: var(--admin-card-bg);
            border-radius: 12px;
            box-shadow: var(--admin-shadow);
            margin-bottom: 1.5rem;
        }

        .admin-header-left {
            display: flex;
            align-items: center;
        }

        .admin-toggle-sidebar {
            background: none;
            border: none;
            color: var(--admin-text);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--admin-transition);
        }

        .admin-toggle-sidebar:hover {
            color: var(--admin-primary);
            transform: scale(1.1);
        }

        .admin-header-title {
            margin-left: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-text);
        }

        .admin-header-right {
            display: flex;
            align-items: center;
        }

        .admin-user-dropdown {
            position: relative;
        }

        .admin-user-button {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            color: var(--admin-text);
        }

        .admin-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--admin-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-primary);
            font-weight: 600;
            margin-right: 0.5rem;
        }

        /* Admin Cards */
        .admin-card {
            background-color: var(--admin-card-bg);
            border-radius: 12px;
            box-shadow: var(--admin-shadow);
            margin-bottom: 1.5rem;
            transition: var(--admin-transition);
            overflow: hidden;
        }

        .admin-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .admin-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--admin-text);
            margin: 0;
        }

        .admin-card-body {
            padding: 1.5rem;
        }

        /* Admin Stats Cards */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .admin-stat-card {
            background-color: var(--admin-card-bg);
            border-radius: 12px;
            box-shadow: var(--admin-shadow);
            padding: 1.5rem;
            transition: var(--admin-transition);
            display: flex;
            flex-direction: column;
        }

        .admin-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .admin-stat-title {
            color: var(--admin-text-light);
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0;
        }

        .admin-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .admin-stat-icon.sales {
            background-color: #493628;
        }

        .admin-stat-icon.products {
            background-color: #10B981;
        }

        .admin-stat-icon.orders {
            background-color: #493628;
        }

        .admin-stat-icon.customers {
            background-color: #F59E0B;
        }

        .admin-stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--admin-text);
            margin: 0.5rem 0;
        }

        .admin-stat-change {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
        }

        .admin-stat-change.positive {
            color: #10B981;
        }

        .admin-stat-change.negative {
            color: #EF4444;
        }

        .admin-stat-change i {
            margin-right: 0.25rem;
        }

        /* Mobile Toggle Button */
        .admin-mobile-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: var(--admin-primary);
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border: none;
            transition: var(--admin-transition);
        }

        .admin-mobile-toggle:hover {
            background-color: var(--admin-secondary);
        }

        .admin-mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        /* Responsive Styles */
        @media (max-width: 991.98px) {
            .admin-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .admin-content.expanded {
                margin-left: 0;
            }
            
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.mobile-visible {
                transform: translateX(0);
            }
            
            .admin-mobile-toggle {
                display: flex;
            }
            
            .admin-mobile-overlay.active {
                display: block;
            }
            
            .admin-header {
                padding: 0 1rem;
            }
            
            .admin-toggle-sidebar {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .admin-stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .admin-header-title {
                font-size: 1.1rem;
            }
            
            .admin-card-header {
                padding: 1rem;
            }
            
            .admin-card-body {
                padding: 1rem;
            }
            
            .admin-content {
                padding: 0.75rem;
            }
        }

        @media (max-width: 575.98px) {
            .admin-header {
                flex-direction: column;
                height: auto;
                padding: 1rem;
            }
            
            .admin-header-left, 
            .admin-header-right {
                width: 100%;
                justify-content: space-between;
            }
            
            .admin-header-right {
                margin-top: 0.5rem;
            }
            
            .admin-user-name {
                display: none;
            }
        }
</style>
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
</style>
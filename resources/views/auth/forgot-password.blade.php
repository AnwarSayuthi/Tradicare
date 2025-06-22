@extends('layout')

@section('css')
<style>
    .auth-container {
        min-height: 100vh;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        width: 100%;
        animation: fadeInUp 0.6s ease-out;
    }

    .auth-title {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .auth-subtitle {
        color: var(--secondary);
        margin-bottom: 2.5rem;
        font-size: 1.1rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
        text-decoration: none;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: var(--primary-dark);
        transform: translateX(-3px);
    }

    .success-message {
        background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
        color: white;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="auth-container d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i>
            Back to Login
        </a>
        
        <h1 class="auth-title text-center">Forgot Password</h1>
        <p class="auth-subtitle text-center">Enter your email address and we'll send you a link to reset your password</p>
        
        @if (session('status'))
            <div class="success-message">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 mb-3">
                <i class="bi bi-envelope me-2"></i>
                Send Reset Link
            </button>
        </form>

        <div class="text-center">
            <p class="mb-0">Remember your password? <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary);">Sign In</a></p>
        </div>
    </div>
</div>
@endsection
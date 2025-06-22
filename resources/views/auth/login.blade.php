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

    .social-login {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    .social-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        color: var(--primary);
    }

    .social-btn:hover {
        transform: translateY(-3px);
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="auth-container d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <h1 class="auth-title text-center">Welcome to Tradicare</h1>
        <p class="auth-subtitle text-center">Please enter your account details</p>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Keep me logged in</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--primary);">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100">Sign in</button>
        </form>

        <div class="social-login">
            <a href="#" class="social-btn">
                <i class="bi bi-google" style="color: var(--primary); font-size: 1.2rem;"></i>
            </a>
            <a href="#" class="social-btn">
                <i class="bi bi-facebook" style="color: var(--primary); font-size: 1.2rem;"></i>
            </a>
            <a href="#" class="social-btn">
                <i class="bi bi-github" style="color: var(--primary); font-size: 1.2rem;"></i>
            </a>
        </div>

        <div class="text-center mt-4">
            <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none" style="color: var(--primary);">Register</a></p>
        </div>
    </div>
</div>
@endsection
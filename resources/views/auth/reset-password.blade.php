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

    .password-requirements {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 1.2rem;
    }
</style>
@endsection

@section('content')
<div class="auth-container d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <h1 class="auth-title text-center">Reset Password</h1>
        <p class="auth-subtitle text-center">Enter your new password below</p>
        
        <div class="password-requirements">
            <strong>Password Requirements:</strong>
            <ul>
                <li>At least 8 characters long</li>
                <li>Must be confirmed by typing it twice</li>
            </ul>
        </div>
        
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email', $request->email) }}" required readonly>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    name="password" required autofocus>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                    name="password_confirmation" required>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 mb-3">
                <i class="bi bi-shield-check me-2"></i>
                Reset Password
            </button>
        </form>

        <div class="text-center">
            <p class="mb-0">Remember your password? <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary);">Sign In</a></p>
        </div>
    </div>
</div>
@endsection
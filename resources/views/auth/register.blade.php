@extends('layout')

@section('css')
<style>
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
    }

    .auth-title {
        font-size: 2rem;
        color: var(--primary);
        font-weight: 600;
    }

    .auth-subtitle {
        color: var(--secondary);
        margin-bottom: 2rem;
    }

    .form-control {
        background: rgba(73, 54, 40, 0.05);
        border: none;
        padding: 1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(73, 54, 40, 0.1);
        box-shadow: none;
    }
</style>
@endsection

@section('content')
<div class="auth-container d-flex align-items-center justify-content-center">
    <div class="auth-card">
        <h1 class="auth-title text-center">Create Account</h1>
        <p class="auth-subtitle text-center">Join our beauty community</p>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                    name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control @error('tel_number') is-invalid @enderror" 
                    name="tel_number" value="{{ old('tel_number') }}" required>
                @error('tel_number')
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

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" 
                    name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100">Create Account</button>

            <div class="text-center mt-4">
                <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary);">Login</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
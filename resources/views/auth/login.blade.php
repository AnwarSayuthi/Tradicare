@extends('layout')

@section('title')
    Login - Tradicare Spa
@endsection

@section('css')
<style type="text/css">
  body {
    background-color: #D6C0B3;
    background-image: linear-gradient(135deg, #D6C0B3 0%, #493628 100%);
    min-height: 100vh;
  }
  .card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: none;
    box-shadow: 0 15px 35px rgba(73, 54, 40, 0.2);
  }
  .btn-primary {
    background-color: #493628;
    border-color: #493628;
    padding: 12px 30px;
    transition: all 0.3s ease;
  }
  .btn-primary:hover {
    background-color: #5a442f;
    border-color: #5a442f;
    transform: translateY(-2px);
  }
  .form-control:focus {
    border-color: #D6C0B3;
    box-shadow: 0 0 0 0.25rem rgba(73, 54, 40, 0.25);
  }
  .link-primary {
    color: #493628;
  }
  .link-primary:hover {
    color: #5a442f;
  }
  .form-check-input:checked {
    background-color: #493628;
    border-color: #493628;
  }
  .invalid-feedback {
    color: #dc3545;
  }
  .card-body {
    padding: 3rem;
  }
  .login-title {
    color: #493628;
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
  }
  .form-floating label {
    color: #666;
  }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card rounded-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{asset('image/logo.png')}}" width="200" alt="Logo Tradicare" class="mb-4">
                        <h1 class="login-title">Welcome Back</h1>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        @session('error')
                            <div class="alert alert-danger" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession

                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" id="email" placeholder="name@example.com" required>
                                <label for="email">{{ __('Email Address') }}</label>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" id="password" placeholder="Password" required>
                                <label for="password">{{ __('Password') }}</label>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{route('password.request')}}" class="link-primary text-decoration-none">
                                {{ __('Forgot password?') }}
                            </a>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" type="submit">
                                {{ __('Sign In') }}
                            </button>
                        </div>

                        <p class="text-center mt-4 mb-0">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="link-primary text-decoration-none">
                                Sign up
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    
@endsection
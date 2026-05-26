@extends('layouts.app')

@section('content')
<div class="min-vh-100 bg-light d-flex align-items-center justify-content-center">
    <div class="row w-100 g-0">
        <!-- Left Panel -->
        <div class="col-md-8 left-panel">
            <div class="left-content">
                <h2>Welcome to {{ config('app.name', 'Attendance System') }}</h2>
                <p class="lead">Please login to access your dashboard</p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="col-md-4 right-panel">
            <div class="login-form">
                <h2 class="text-center mb-4">Login</h2>
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-signin w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.left-panel {
    flex: 1;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%),
                        url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&auto=format&fit=crop') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    min-height: 100vh;
}

.left-content {
    text-align: center;
    max-width: 400px;
}

.left-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.left-content p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.right-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.login-form {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

.login-form h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.login-form p {
    color: #666;
    margin-bottom: 2rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.input-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.input-group-text {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #1a365d;
    box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
}

.form-check {
    margin-bottom: 1rem;
}

.form-check-input {
    margin-right: 0.5rem;
}

.form-check-label {
    color: #6c757d;
}

.btn-signin {
    width: 100%;
    padding: 0.75rem;
    background: #1a365d;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.btn-signin:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 54, 93, 0.3);
}

.text-primary {
    color: #1a365d;
    text-decoration: none;
    font-weight: 600;
}

.text-primary:hover {
    text-decoration: underline;
}

.alert {
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

@media (max-width: 768px) {
    .left-panel {
        min-height: 100vh;
        padding: 1rem;
    }
    
    .right-panel {
        padding: 1rem;
    }
    
    .login-form {
        margin: 0;
        max-width: 100%;
    }
}
</style>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

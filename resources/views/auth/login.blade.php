@extends('auth.layouts.auth')

@section('title', 'Login')

@section('left-content')
    <div class="feature-list">
        <div class="feature-item">
            <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 2v20M2 12h20"/>
            </svg>
            <div>
                <h3>Strategic Gameplay</h3>
                <p>Command fleets and conquer galaxies</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
            </svg>
            <div>
                <h3>Join Alliances</h3>
                <p>Team up with players worldwide</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <div>
                <h3>Climb Rankings</h3>
                <p>Compete for galactic supremacy</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Login to continue your conquest</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
            @csrf
            
            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input @error('email') is-invalid @enderror" 
                        placeholder="commander@galaxy.com"
                        value="{{ old('email') }}"
                        required 
                        autofocus
                    >
                </div>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') is-invalid @enderror" 
                        placeholder="Enter your password"
                        required
                    >
                    <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-off-icon hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Remember Me and Forgot Password -->
            <div class="form-row">
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="form-checkbox"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="form-check-label">Remember me</label>
                </div>
                <a href="#" class="form-link">Forgot password?</a>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">
                <span>Login to Account</span>
                <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
            
            <!-- Divider -->
            <div class="form-divider">
                <span>or</span>
            </div>
            
            <!-- Register Link -->
            <p class="auth-switch">
                Don't have an account? 
                <a href="{{ route('register') }}" class="link-primary">Create Account</a>
            </p>
        </form>
    </div>
@endsection

@extends('auth.layouts.auth')

@section('title', 'Register')

@section('left-content')
    <div class="stats-container">
        <div class="stat-card">
            <span class="stat-number">{{ number_format(rand(15000, 25000)) }}</span>
            <span class="stat-label">Active Players</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ number_format(rand(50000, 100000)) }}</span>
            <span class="stat-label">Planets Colonized</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">{{ number_format(rand(1000, 5000)) }}</span>
            <span class="stat-label">Battles Today</span>
        </div>
    </div>
    
    <div class="auth-testimonial">
        <p class="testimonial-text">"Join the epic space adventure and build your galactic empire. Command massive fleets, forge alliances, and become a legend."</p>
        <div class="testimonial-rating">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
    </div>
@endsection

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Begin Your Journey</h2>
            <p class="auth-subtitle">Create your account and conquer the universe</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
            @csrf
            
            <!-- Username Field -->
            <div class="form-group">
                <label for="username" class="form-label">Commander Name</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <input 
                        type="text" 
                        id="username" 
                        name="name" 
                        class="form-input @error('name') is-invalid @enderror" 
                        placeholder="Enter your commander name"
                        value="{{ old('name') }}"
                        required 
                        autofocus
                    >
                </div>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
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
                        placeholder="Create a strong password"
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
                
                <!-- Password Strength Meter -->
                <div class="password-strength">
                    <div class="strength-meter">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <span class="strength-text" id="strengthText">Enter a password</span>
                </div>
                
                <!-- Password Requirements -->
                <div class="password-requirements">
                    <div class="requirement" data-requirement="length">
                        <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>At least 8 characters</span>
                    </div>
                    <div class="requirement" data-requirement="lowercase">
                        <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>One lowercase letter</span>
                    </div>
                    <div class="requirement" data-requirement="uppercase">
                        <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>One uppercase letter</span>
                    </div>
                    <div class="requirement" data-requirement="number">
                        <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>One number</span>
                    </div>
                </div>
                
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Confirm your password"
                        required
                    >
                    <button type="button" class="password-toggle" id="confirmPasswordToggle" aria-label="Toggle password visibility">
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
            </div>
            
            <!-- Terms and Conditions -->
            <div class="form-group">
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        class="form-checkbox"
                        required
                    >
                    <label for="terms" class="form-check-label">
                        I agree to the <a href="#" class="link-inline">Terms & Conditions</a> and <a href="#" class="link-inline">Privacy Policy</a>
                    </label>
                </div>
                @error('terms')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">
                <span>Create Account</span>
                <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
            
            <!-- Divider -->
            <div class="form-divider">
                <span>or</span>
            </div>
            
            <!-- Login Link -->
            <p class="auth-switch">
                Already have an account? 
                <a href="{{ route('login') }}" class="link-primary">Login</a>
            </p>
        </form>
    </div>
@endsection

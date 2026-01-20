@extends('auth.layouts.auth')

@section('title', 'Join OGameX')

@section('left-content')
<div class="branding-content">
    <h1 class="branding-title">Welcome to OGameX</h1>
    <p class="branding-subtitle">The Ultimate Space Strategy Game</p>
    
    <div class="features-list">
        <div class="feature-item">
            <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v6l4 2"></path>
            </svg>
            <div>
                <h3>Quick Start</h3>
                <p>Get into the game in under 60 seconds</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
            </svg>
            <div>
                <h3>Instant Setup</h3>
                <p>Your empire awaits with starting resources ready</p>
            </div>
        </div>
        <div class="feature-item">
            <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
            <div>
                <h3>Guided Tutorial</h3>
                <p>Learn as you play with our interactive guide</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('form-content')
<div class="auth-form-container" id="authContainer">
    <!-- Step 1: Email Entry -->
    <div class="auth-step" id="emailStep">
        <div class="form-header">
            <h2 class="form-title">Start Your Journey</h2>
            <p class="form-subtitle">Enter your email to continue</p>
        </div>

        <form id="emailForm" class="auth-form" method="POST" action="javascript:void(0);">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-10 5L2 7"></path>
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="commander@space.fleet" required autofocus>
                </div>
                <span class="error-message" id="emailError"></span>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span class="btn-text">Continue</span>
                <span class="btn-loader hidden">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="32" stroke-dashoffset="32">
                            <animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" from="32" to="0"/>
                        </circle>
                    </svg>
                </span>
            </button>
        </form>
    </div>

    <!-- Step 2A: Login (Existing User) -->
    <div class="auth-step hidden" id="loginStep">
        <div class="form-header">
            <button class="back-button" onclick="goBackToEmail()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </button>
            <h2 class="form-title">Welcome Back!</h2>
            <p class="form-subtitle">Enter your password to continue</p>
        </div>

        <form id="loginForm" class="auth-form" action="{{ route('login') }}" method="POST">
            @csrf
            <input type="hidden" name="email" id="loginEmail">
            
            <div class="form-group">
                <div class="user-email-display">
                    <span class="email-label">Logging in as:</span>
                    <span class="email-value" id="displayEmail"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="loginPassword" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0110 0v4"></path>
                        </svg>
                    </span>
                    <input type="password" id="loginPassword" name="password" class="form-input" 
                           placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" id="loginPasswordToggle">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-off-icon hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                <span class="error-message" id="loginPasswordError"></span>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" class="checkbox-input">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span class="btn-text">Launch Game</span>
                <span class="btn-loader hidden">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="32" stroke-dashoffset="32">
                            <animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" from="32" to="0"/>
                        </circle>
                    </svg>
                </span>
            </button>

            <div class="form-footer">
                <a href="#" class="link">Forgot password?</a>
            </div>
        </form>
    </div>

    <!-- Step 2B: Registration (New User) -->
    <div class="auth-step hidden" id="registerStep">
        <div class="form-header">
            <button class="back-button" onclick="goBackToEmail()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </button>
            <h2 class="form-title">Create Your Empire</h2>
            <p class="form-subtitle">Choose your commander name</p>
        </div>

        <form id="registerForm" class="auth-form">
            @csrf
            <input type="hidden" name="email" id="registerEmail">
            
            <div class="form-group">
                <div class="user-email-display">
                    <span class="email-label">Registering with:</span>
                    <span class="email-value" id="displayEmailRegister"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Commander Name</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <input type="text" id="username" name="username" class="form-input" 
                           placeholder="Choose your commander name" required minlength="3" maxlength="20">
                    <span class="availability-indicator" id="usernameAvailability"></span>
                </div>
                <span class="helper-text">3-20 characters, letters and numbers only</span>
                <span class="error-message" id="usernameError"></span>
            </div>

            <div class="form-group">
                <label for="registerPassword" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0110 0v4"></path>
                        </svg>
                    </span>
                    <input type="password" id="registerPassword" name="password" class="form-input" 
                           placeholder="Create a strong password" required minlength="8">
                    <button type="button" class="password-toggle" id="registerPasswordToggle">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg class="eye-off-icon hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                
                <div class="password-strength">
                    <div class="strength-meter">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <span class="strength-text" id="strengthText">Enter a password</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" id="continueToNaming" disabled>
                <span class="btn-text">Continue to Planet Naming</span>
                <span class="btn-loader hidden">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="32" stroke-dashoffset="32">
                            <animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" from="32" to="0"/>
                        </circle>
                    </svg>
                </span>
            </button>
        </form>
    </div>

    <!-- Step 3: Planet Naming -->
    <div class="auth-step hidden" id="planetStep">
        <div class="form-header">
            <h2 class="form-title">Name Your Home Planet</h2>
            <p class="form-subtitle">Every empire needs a capital</p>
        </div>

        <form id="planetForm" class="auth-form" action="{{ route('register') }}" method="POST">
            @csrf
            <input type="hidden" name="email" id="finalEmail">
            <input type="hidden" name="username" id="finalUsername">
            <input type="hidden" name="password" id="finalPassword">
            
            <div class="planet-preview">
                <div class="planet-image">
                    <img src="/img/planets/planet_1.png" alt="Your home planet">
                </div>
                <div class="planet-info">
                    <p>Your starting planet will have:</p>
                    <ul class="starting-resources">
                        <li><span class="resource-icon metal"></span> 500 Metal</li>
                        <li><span class="resource-icon crystal"></span> 500 Crystal</li>
                        <li><span class="resource-icon deuterium"></span> 0 Deuterium</li>
                        <li><span class="resource-icon energy"></span> Basic Infrastructure</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="planetName" class="form-label">Planet Name</label>
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                            <ellipse cx="12" cy="12" rx="10" ry="4"></ellipse>
                            <path d="M2 12h20"></path>
                        </svg>
                    </span>
                    <input type="text" id="planetName" name="planet_name" class="form-input" 
                           placeholder="e.g., Terra Prime, New Eden" required maxlength="20" value="Homeworld">
                </div>
                <span class="helper-text">You can rename it later</span>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" class="checkbox-input" required>
                    <span>I accept the <a href="#" class="link">Terms of Service</a> and <a href="#" class="link">Privacy Policy</a></span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-glow">
                <span class="btn-text">Launch My Empire!</span>
                <span class="btn-loader hidden">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="32" stroke-dashoffset="32">
                            <animate attributeName="stroke-dashoffset" dur="1s" repeatCount="indefinite" from="32" to="0"/>
                        </circle>
                    </svg>
                </span>
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log('Inline script executing');
    // Test if button exists
    window.addEventListener('load', function() {
        const form = document.getElementById('emailForm');
        const button = form?.querySelector('button[type="submit"]');
        console.log('Form found:', !!form);
        console.log('Button found:', !!button);
        if (button) {
            console.log('Button text:', button.textContent);
        }
    });
</script>
<script src="{{ asset('js/onboarding.js') }}"></script>
@endsection

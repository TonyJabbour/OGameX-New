// OGameX Onboarding Flow JavaScript

console.log('Onboarding.js loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing onboarding');
    
    // State management
    const state = {
        email: '',
        username: '',
        password: '',
        planetName: 'Homeworld',
        currentStep: 'email'
    };

    // Cache DOM elements
    const elements = {
        emailStep: document.getElementById('emailStep'),
        loginStep: document.getElementById('loginStep'),
        registerStep: document.getElementById('registerStep'),
        planetStep: document.getElementById('planetStep'),
        emailForm: document.getElementById('emailForm'),
        loginForm: document.getElementById('loginForm'),
        registerForm: document.getElementById('registerForm'),
        planetForm: document.getElementById('planetForm'),
        emailInput: document.getElementById('email'),
        usernameInput: document.getElementById('username'),
        loginPasswordInput: document.getElementById('loginPassword'),
        registerPasswordInput: document.getElementById('registerPassword'),
        planetNameInput: document.getElementById('planetName'),
        strengthBar: document.getElementById('strengthBar'),
        strengthText: document.getElementById('strengthText'),
        usernameAvailability: document.getElementById('usernameAvailability'),
        continueButton: document.getElementById('continueToNaming')
    };

    console.log('Elements cached:', {
        emailForm: !!elements.emailForm,
        emailInput: !!elements.emailInput,
        emailStep: !!elements.emailStep
    });

    // Step 1: Email submission
    if (elements.emailForm) {
        console.log('Email form found, attaching event listener');
        elements.emailForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Email form submitted');
            
            const email = elements.emailInput.value.trim();
            console.log('Email entered:', email);
            if (!validateEmail(email)) {
                showError('emailError', 'Please enter a valid email address');
                return;
            }

            state.email = email;
            showLoader(e.target.querySelector('button'));
            
            try {
                // Check if email exists
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch('/api/auth/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                hideLoader(e.target.querySelector('button'));

                if (data.exists) {
                    // Existing user - show login
                    showLoginStep(email);
                } else {
                    // New user - show registration
                    showRegisterStep(email);
                }
            } catch (error) {
                console.error('Email check error:', error);
                hideLoader(e.target.querySelector('button'));
                showError('emailError', 'Unable to verify email. Please check your connection and try again.');
            }
        });
    }

    // Username availability check
    let usernameCheckTimeout;
    if (elements.usernameInput) {
        elements.usernameInput.addEventListener('input', function(e) {
            const username = e.target.value.trim();
            
            // Clear previous timeout
            clearTimeout(usernameCheckTimeout);
            clearError('usernameError');
            
            // Reset availability indicator
            elements.usernameAvailability.className = 'availability-indicator';
            elements.usernameAvailability.textContent = '';
            
            if (username.length < 3) {
                elements.continueButton.disabled = true;
                return;
            }

            // Validate username format
            if (!validateUsername(username)) {
                showError('usernameError', 'Only letters and numbers allowed');
                elements.continueButton.disabled = true;
                return;
            }

            // Show checking indicator
            elements.usernameAvailability.className = 'availability-indicator checking';
            elements.usernameAvailability.innerHTML = '<span class="spinner-small"></span> Checking...';
            
            // Debounced availability check
            usernameCheckTimeout = setTimeout(async () => {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const response = await fetch('/api/auth/check-username', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ username })
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (data.available) {
                        elements.usernameAvailability.className = 'availability-indicator available';
                        elements.usernameAvailability.innerHTML = '✓ Available';
                        state.username = username;
                        checkRegisterFormValidity();
                    } else {
                        elements.usernameAvailability.className = 'availability-indicator taken';
                        elements.usernameAvailability.innerHTML = '✗ Already taken';
                        elements.continueButton.disabled = true;
                    }
                } catch (error) {
                    console.error('Username check error:', error);
                    elements.usernameAvailability.className = 'availability-indicator';
                    elements.usernameAvailability.textContent = '';
                    showError('usernameError', 'Unable to check availability. Please try again.');
                }
            }, 500);
        });
    }

    // Password strength meter
    if (elements.registerPasswordInput) {
        elements.registerPasswordInput.addEventListener('input', function(e) {
            const password = e.target.value;
            checkPasswordStrength(password);
            state.password = password;
            checkRegisterFormValidity();
        });
    }

    // Password toggle functionality
    setupPasswordToggle('loginPasswordToggle', 'loginPassword');
    setupPasswordToggle('registerPasswordToggle', 'registerPassword');

    // Registration form submission
    if (elements.registerForm) {
        elements.registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateRegistrationForm()) {
                return;
            }

            // Move to planet naming step
            showPlanetStep();
        });
    }

    // Planet form submission (final registration)
    if (elements.planetForm) {
        elements.planetForm.addEventListener('submit', function(e) {
            // Set hidden field values
            document.getElementById('finalEmail').value = state.email;
            document.getElementById('finalUsername').value = state.username;
            document.getElementById('finalPassword').value = state.password;
            
            // Show loader
            showLoader(e.target.querySelector('button'));
            
            // Form will submit normally to register route
        });
    }

    // Helper Functions
    function showLoginStep(email) {
        elements.emailStep.classList.add('hidden');
        elements.loginStep.classList.remove('hidden');
        document.getElementById('loginEmail').value = email;
        document.getElementById('displayEmail').textContent = email;
        elements.loginPasswordInput.focus();
        state.currentStep = 'login';
    }

    function showRegisterStep(email) {
        elements.emailStep.classList.add('hidden');
        elements.registerStep.classList.remove('hidden');
        document.getElementById('registerEmail').value = email;
        document.getElementById('displayEmailRegister').textContent = email;
        elements.usernameInput.focus();
        state.currentStep = 'register';
    }

    function showPlanetStep() {
        elements.registerStep.classList.add('hidden');
        elements.planetStep.classList.remove('hidden');
        elements.planetNameInput.focus();
        state.currentStep = 'planet';
        
        // Animate planet image
        const planetImage = document.querySelector('.planet-image img');
        if (planetImage) {
            planetImage.style.animation = 'planetRotate 30s linear infinite';
        }
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validateUsername(username) {
        const re = /^[a-zA-Z0-9]+$/;
        return re.test(username);
    }

    function checkPasswordStrength(password) {
        const requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*]/.test(password)
        };
        
        const metRequirements = Object.values(requirements).filter(r => r).length;
        
        elements.strengthBar.className = 'strength-bar';
        elements.strengthText.className = 'strength-text';
        
        if (password.length === 0) {
            elements.strengthBar.style.width = '0';
            elements.strengthText.textContent = 'Enter a password';
        } else if (metRequirements < 2) {
            elements.strengthBar.classList.add('weak');
            elements.strengthBar.style.width = '33%';
            elements.strengthText.classList.add('weak');
            elements.strengthText.textContent = 'Weak';
        } else if (metRequirements < 4) {
            elements.strengthBar.classList.add('medium');
            elements.strengthBar.style.width = '66%';
            elements.strengthText.classList.add('medium');
            elements.strengthText.textContent = 'Medium';
        } else {
            elements.strengthBar.classList.add('strong');
            elements.strengthBar.style.width = '100%';
            elements.strengthText.classList.add('strong');
            elements.strengthText.textContent = 'Strong';
        }
    }

    function checkRegisterFormValidity() {
        const usernameValid = state.username.length >= 3 && 
                            elements.usernameAvailability.classList.contains('available');
        const passwordValid = state.password.length >= 8;
        
        elements.continueButton.disabled = !(usernameValid && passwordValid);
    }

    function validateRegistrationForm() {
        if (!state.username || state.username.length < 3) {
            showError('usernameError', 'Username must be at least 3 characters');
            return false;
        }
        
        if (!state.password || state.password.length < 8) {
            showError('registerPasswordError', 'Password must be at least 8 characters');
            return false;
        }
        
        return true;
    }

    function setupPasswordToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        
        if (toggle && input) {
            toggle.addEventListener('click', function() {
                const eyeIcon = this.querySelector('.eye-icon');
                const eyeOffIcon = this.querySelector('.eye-off-icon');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        }
    }

    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
        }
    }

    function clearError(elementId) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = '';
        }
    }

    function showLoader(button) {
        if (button) {
            button.disabled = true;
            button.querySelector('.btn-text').classList.add('hidden');
            button.querySelector('.btn-loader').classList.remove('hidden');
        }
    }

    function hideLoader(button) {
        if (button) {
            button.disabled = false;
            button.querySelector('.btn-text').classList.remove('hidden');
            button.querySelector('.btn-loader').classList.add('hidden');
        }
    }

    // Global function for back button
    window.goBackToEmail = function() {
        elements.loginStep.classList.add('hidden');
        elements.registerStep.classList.add('hidden');
        elements.emailStep.classList.remove('hidden');
        elements.emailInput.focus();
        state.currentStep = 'email';
    };
});

// CSS animation for planet rotation
const style = document.createElement('style');
style.textContent = `
    @keyframes planetRotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

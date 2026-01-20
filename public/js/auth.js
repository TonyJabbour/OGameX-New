// OGameX Authentication Pages JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Password Toggle Functionality
    const setupPasswordToggle = (toggleId, inputId) => {
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
    };
    
    // Setup password toggles
    setupPasswordToggle('passwordToggle', 'password');
    setupPasswordToggle('confirmPasswordToggle', 'password_confirmation');
    
    // Password Strength Meter
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const requirements = document.querySelectorAll('.requirement');
    
    if (passwordInput && strengthBar && strengthText) {
        const checkPasswordStrength = (password) => {
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /[0-9]/.test(password)
            };
            
            // Update requirements UI
            Object.keys(requirements).forEach(req => {
                const element = document.querySelector(`[data-requirement="${req}"]`);
                if (element) {
                    if (requirements[req]) {
                        element.classList.add('met');
                    } else {
                        element.classList.remove('met');
                    }
                }
            });
            
            // Calculate strength
            const metRequirements = Object.values(requirements).filter(r => r).length;
            
            // Update strength bar and text
            strengthBar.className = 'strength-bar';
            strengthText.className = 'strength-text';
            
            if (password.length === 0) {
                strengthBar.style.width = '0';
                strengthText.textContent = 'Enter a password';
                strengthText.className = 'strength-text';
            } else if (metRequirements < 2) {
                strengthBar.classList.add('weak');
                strengthText.classList.add('weak');
                strengthText.textContent = 'Weak password';
            } else if (metRequirements < 4) {
                strengthBar.classList.add('medium');
                strengthText.classList.add('medium');
                strengthText.textContent = 'Medium strength';
            } else {
                strengthBar.classList.add('strong');
                strengthText.classList.add('strong');
                strengthText.textContent = 'Strong password';
            }
        };
        
        // Listen for password input
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
        
        // Check on page load if password has value (e.g., from browser autofill)
        if (passwordInput.value) {
            checkPasswordStrength(passwordInput.value);
        }
    }
    
    // Form Validation
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    const validateEmail = (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    };
    
    const showError = (input, message) => {
        input.classList.add('is-invalid');
        const errorElement = input.closest('.form-group').querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = message;
        } else {
            const error = document.createElement('span');
            error.className = 'error-message';
            error.textContent = message;
            input.closest('.form-group').appendChild(error);
        }
    };
    
    const clearError = (input) => {
        input.classList.remove('is-invalid');
        const errorElement = input.closest('.form-group').querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    };
    
    // Login Form Validation
    if (loginForm) {
        const emailInput = loginForm.querySelector('#email');
        const passwordInput = loginForm.querySelector('#password');
        const submitButton = loginForm.querySelector('button[type="submit"]');
        
        // Auto-focus email field
        if (emailInput) {
            emailInput.focus();
        }
        
        // Email validation on blur
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                if (this.value && !validateEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });
            
            emailInput.addEventListener('input', function() {
                clearError(this);
            });
        }
        
        // Password validation on blur
        if (passwordInput) {
            passwordInput.addEventListener('blur', function() {
                if (this.value && this.value.length < 4) {
                    showError(this, 'Password must be at least 4 characters');
                } else {
                    clearError(this);
                }
            });
            
            passwordInput.addEventListener('input', function() {
                clearError(this);
            });
        }
        
        // Form submission
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (!emailInput.value) {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (!validateEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            }
            
            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // Add loading state to button
            if (submitButton) {
                submitButton.classList.add('btn-loading');
                submitButton.disabled = true;
            }
        });
    }
    
    // Register Form Validation
    if (registerForm) {
        const nameInput = registerForm.querySelector('#username');
        const emailInput = registerForm.querySelector('#email');
        const passwordInput = registerForm.querySelector('#password');
        const confirmPasswordInput = registerForm.querySelector('#password_confirmation');
        const termsCheckbox = registerForm.querySelector('#terms');
        const submitButton = registerForm.querySelector('button[type="submit"]');
        
        // Auto-focus username field
        if (nameInput) {
            nameInput.focus();
        }
        
        // Name validation
        if (nameInput) {
            nameInput.addEventListener('blur', function() {
                if (this.value && this.value.length < 3) {
                    showError(this, 'Commander name must be at least 3 characters');
                } else if (this.value && this.value.length > 20) {
                    showError(this, 'Commander name must be less than 20 characters');
                } else {
                    clearError(this);
                }
            });
            
            nameInput.addEventListener('input', function() {
                clearError(this);
            });
        }
        
        // Email validation
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                if (this.value && !validateEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });
            
            emailInput.addEventListener('input', function() {
                clearError(this);
            });
        }
        
        // Password confirmation validation
        if (confirmPasswordInput && passwordInput) {
            const validatePasswordMatch = () => {
                if (confirmPasswordInput.value && confirmPasswordInput.value !== passwordInput.value) {
                    showError(confirmPasswordInput, 'Passwords do not match');
                } else {
                    clearError(confirmPasswordInput);
                }
            };
            
            confirmPasswordInput.addEventListener('blur', validatePasswordMatch);
            confirmPasswordInput.addEventListener('input', function() {
                clearError(this);
            });
            
            // Also check when password changes
            passwordInput.addEventListener('change', validatePasswordMatch);
        }
        
        // Form submission
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (!nameInput.value) {
                showError(nameInput, 'Commander name is required');
                isValid = false;
            }
            
            if (!emailInput.value) {
                showError(emailInput, 'Email is required');
                isValid = false;
            } else if (!validateEmail(emailInput.value)) {
                showError(emailInput, 'Please enter a valid email address');
                isValid = false;
            }
            
            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required');
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                showError(passwordInput, 'Password must be at least 8 characters');
                isValid = false;
            }
            
            if (!confirmPasswordInput.value) {
                showError(confirmPasswordInput, 'Please confirm your password');
                isValid = false;
            } else if (confirmPasswordInput.value !== passwordInput.value) {
                showError(confirmPasswordInput, 'Passwords do not match');
                isValid = false;
            }
            
            if (!termsCheckbox.checked) {
                showError(termsCheckbox, 'You must accept the terms and conditions');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // Add loading state to button
            if (submitButton) {
                submitButton.classList.add('btn-loading');
                submitButton.disabled = true;
            }
        });
    }
    
    // Animated background parallax effect
    const spaceBackground = document.querySelector('.space-background');
    if (spaceBackground) {
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            const stars1 = document.querySelector('.stars');
            const stars2 = document.querySelector('.stars2');
            const stars3 = document.querySelector('.stars3');
            
            if (stars1) {
                stars1.style.transform = `translate(${x * 20}px, ${y * 20}px)`;
            }
            if (stars2) {
                stars2.style.transform = `translate(${x * -10}px, ${y * -10}px)`;
            }
            if (stars3) {
                stars3.style.transform = `translate(${x * 15}px, ${y * 15}px)`;
            }
        });
    }
    
    // Add smooth transitions for form inputs
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
});

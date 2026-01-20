<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OGameX') }} - @yield('title', 'Authentication')</title>
    <meta name="description" content="OGameX - Join the ultimate space strategy game and build your galactic empire.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="auth-page">
    <!-- Animated Background -->
    <div class="space-background">
        <div class="stars"></div>
        <div class="stars2"></div>
        <div class="stars3"></div>
        <div class="nebula"></div>
    </div>
    
    <!-- Main Container -->
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- Left Panel - Branding -->
            <div class="auth-left">
                <div class="auth-brand">
                    <a href="/" class="logo">
                        <h1 class="logo-text">OGameX</h1>
                    </a>
                    <p class="brand-tagline">Conquer the Universe</p>
                </div>
                
                <div class="auth-features">
                    @yield('left-content')
                </div>
                
                <div class="auth-footer">
                    <p>&copy; {{ date('Y') }} OGameX. All rights reserved.</p>
                </div>
            </div>
            
            <!-- Right Panel - Form -->
            <div class="auth-right">
                <div class="auth-form-container">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Logo (shown only on mobile) -->
    <div class="mobile-header">
        <a href="/" class="mobile-logo">
            <h1 class="logo-text">OGameX</h1>
        </a>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/auth.js') }}"></script>
    @stack('scripts')
</body>
</html>

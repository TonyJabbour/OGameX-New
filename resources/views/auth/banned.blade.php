@extends('auth.layouts.auth')

@section('title', 'Account Banned')

@section('left-content')
    <div class="feature-item">
        <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
        </svg>
        <div>
            <h3>Access Restricted</h3>
            <p>Your account has been suspended</p>
        </div>
    </div>
    <div class="feature-item">
        <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
        </svg>
        <div>
            <h3>Review Our Rules</h3>
            <p>Understand community guidelines</p>
        </div>
    </div>
    <div class="feature-item">
        <svg class="feature-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <div>
            <h3>Contact Support</h3>
            <p>Questions about your ban</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title">Account Suspended</h2>
            <p class="auth-subtitle">Your account has been temporarily restricted</p>
        </div>
        
        <!-- Ban Details Alert -->
        <div class="alert alert-danger">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
            <strong>Ban Details</strong>
        </div>

        <div class="auth-form">
            <div class="info-card">
                <div class="info-item">
                    <span class="info-label">Reason</span>
                    <span class="info-value">{{ auth()->user()->ban_reason ?? 'No reason provided' }}</span>
                </div>
                
                @if(auth()->user()->banned_at)
                <div class="info-item">
                    <span class="info-label">Banned On</span>
                    <span class="info-value">{{ auth()->user()->banned_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
                @endif
                
                <div class="info-item">
                    <span class="info-label">Ban Expires</span>
                    <span class="info-value">
                        @if(auth()->user()->banned_until)
                            {{ auth()->user()->banned_until->format('F j, Y \a\t g:i A') }}
                        @else
                            Permanent
                        @endif
                    </span>
                </div>
                
                @if(auth()->user()->banned_until)
                <div class="alert alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Your ban will be automatically lifted in <strong>{{ auth()->user()->banned_until->diffForHumans() }}</strong>
                </div>
                @endif
            </div>

            <div class="info-section">
                <h3>What This Means</h3>
                <ul>
                    <li>You cannot access your account or game features</li>
                    <li>Your planets and fleets are frozen</li>
                    <li>You cannot send or receive messages</li>
                    <li>Your account remains in the database</li>
                </ul>
            </div>

            <div class="info-section">
                <h3>Need Help?</h3>
                <p>If you believe this ban was issued in error or have questions about your suspension, you can:</p>
                <ul>
                    <li>Review the game rules and terms of service</li>
                    <li>Contact support via email</li>
                    <li>Submit an appeal if this was a mistake</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="auth-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <style>
        .alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .info-item:first-child {
            padding-top: 0;
        }
        
        .info-label {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
        }
        
        .info-value {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            text-align: right;
        }
        
        .info-section {
            margin-bottom: 1.5rem;
        }
        
        .info-section h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .info-section p {
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }
        
        .info-section ul {
            margin: 0;
            padding-left: 1.25rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.8;
        }
        
        .info-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .info-section ul li:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection

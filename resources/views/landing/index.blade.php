@extends('landing.layouts.landing')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="title-line">Conquer the</span>
                    <span class="title-line gradient-text">Universe</span>
                </h1>
                <p class="hero-subtitle">
                    Build your empire, command massive fleets, and dominate the galaxy in the ultimate space strategy game.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('auth.unified') }}" class="btn btn-primary">
                        <span>Play Now</span>
                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        <span>Learn More</span>
                    </a>
                </div>
                
                <!-- Live Stats -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['active_players'] }}">0</span>
                        <span class="stat-label">Active Players</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['planets_colonized'] }}">0</span>
                        <span class="stat-label">Planets Colonized</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="{{ $stats['battles_today'] }}">0</span>
                        <span class="stat-label">Battles Today</span>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image/Animation -->
            <div class="hero-visual">
                <div class="planet-container">
                    <div class="planet"></div>
                    <div class="planet-ring"></div>
                    <div class="ships">
                        <div class="ship ship-1"></div>
                        <div class="ship ship-2"></div>
                        <div class="ship ship-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Epic Features</h2>
                <p class="section-subtitle">Everything you need to build your galactic empire</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2v20M2 12h20"/>
                            <path d="M8.5 8.5L15.5 15.5M15.5 8.5L8.5 15.5"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Strategic Combat</h3>
                    <p class="feature-description">
                        Engage in epic space battles with advanced fleet tactics and real-time strategy elements.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                            <path d="M12 11v6M12 7h.01"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Alliance System</h3>
                    <p class="feature-description">
                        Join powerful alliances, coordinate attacks, and share resources with your allies.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.01" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Resource Management</h3>
                    <p class="feature-description">
                        Mine resources, manage production, and build a thriving economy across multiple planets.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2v7M12 15v7M4.22 10.22l4.95 1.78M14.83 16l4.95 1.78M4.22 13.78l4.95-1.78M14.83 8l4.95-1.78"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Technology Tree</h3>
                    <p class="feature-description">
                        Research advanced technologies to unlock powerful ships and devastating weapons.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Ranking System</h3>
                    <p class="feature-description">
                        Climb the leaderboards and prove your dominance in multiple ranking categories.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Real-Time Events</h3>
                    <p class="feature-description">
                        Experience dynamic events, expeditions, and special missions for unique rewards.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Screenshots Section -->
    <section id="screenshots" class="screenshots">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Game Screenshots</h2>
                <p class="section-subtitle">Experience the universe in stunning detail</p>
            </div>
            
            <div class="carousel">
                <div class="carousel-container">
                    <div class="carousel-track">
                        <div class="carousel-slide active">
                            <img src="/img/screenshots/galaxy-view.jpg" alt="Galaxy View" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1920 1080\'%3E%3Crect fill=\'%231e293b\' width=\'1920\' height=\'1080\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23cbd5e1\' font-size=\'48\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-family=\'sans-serif\'%3EGalaxy View%3C/text%3E%3C/svg%3E'">
                            <div class="slide-caption">Explore the Galaxy</div>
                        </div>
                        <div class="carousel-slide">
                            <img src="/img/screenshots/fleet-combat.jpg" alt="Fleet Combat" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1920 1080\'%3E%3Crect fill=\'%231e293b\' width=\'1920\' height=\'1080\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23cbd5e1\' font-size=\'48\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-family=\'sans-serif\'%3EFleet Combat%3C/text%3E%3C/svg%3E'">
                            <div class="slide-caption">Epic Space Battles</div>
                        </div>
                        <div class="carousel-slide">
                            <img src="/img/screenshots/planet-view.jpg" alt="Planet View" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1920 1080\'%3E%3Crect fill=\'%231e293b\' width=\'1920\' height=\'1080\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23cbd5e1\' font-size=\'48\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-family=\'sans-serif\'%3EPlanet Management%3C/text%3E%3C/svg%3E'">
                            <div class="slide-caption">Manage Your Planets</div>
                        </div>
                        <div class="carousel-slide">
                            <img src="/img/screenshots/tech-tree.jpg" alt="Technology Tree" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1920 1080\'%3E%3Crect fill=\'%231e293b\' width=\'1920\' height=\'1080\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23cbd5e1\' font-size=\'48\' text-anchor=\'middle\' dominant-baseline=\'middle\' font-family=\'sans-serif\'%3ETechnology Tree%3C/text%3E%3C/svg%3E'">
                            <div class="slide-caption">Research Technologies</div>
                        </div>
                    </div>
                </div>
                <button class="carousel-btn carousel-prev" aria-label="Previous">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button class="carousel-btn carousel-next" aria-label="Next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
                <div class="carousel-indicators">
                    <button class="indicator active" data-slide="0"></button>
                    <button class="indicator" data-slide="1"></button>
                    <button class="indicator" data-slide="2"></button>
                    <button class="indicator" data-slide="3"></button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Conquer the Universe?</h2>
                <p class="cta-subtitle">Join thousands of players in the ultimate space strategy experience</p>
                <a href="{{ route('auth.unified') }}" class="btn btn-primary btn-large btn-glow">
                    <span>Start Your Journey</span>
                    <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="cta-visual">
                <div class="cta-stars"></div>
            </div>
        </div>
    </section>
@endsection

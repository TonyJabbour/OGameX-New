@extends('admin.layouts.admin')

@section('title', 'User Details - ' . $user->username)

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title">{{ $user->username }}</h1>
            <p class="page-description">User ID: #{{ $user->id }} • Registered {{ $user->created_at->diffForHumans() }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>
    </div>
</div>

<!-- User Header Card -->
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; gap: 2rem;">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 2rem; box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);">
            {{ substr($user->username, 0, 1) }}
        </div>
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                <h2 style="font-size: 1.75rem; font-weight: 700;">{{ $user->username }}</h2>
                @if($user->hasRole('admin'))
                    <span class="badge" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #1e293b; border: none;">ADMIN</span>
                @endif
                @if($user->is_banned)
                    <span class="badge badge-danger">BANNED</span>
                @endif
                @if($user->vacation_mode)
                    <span class="badge badge-warning">ON VACATION</span>
                @elseif($user->isOnline())
                    <span class="badge badge-success">ONLINE</span>
                @endif
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Email</div>
                    <div style="font-weight: 500;">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Dark Matter</div>
                    <div style="font-weight: 600; color: var(--primary); font-family: monospace;">{{ number_format($user->dark_matter) }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Character Class</div>
                    <div style="font-weight: 500;">
                        @if($user->character_class === 1)
                            Collector
                        @elseif($user->character_class === 2)
                            General
                        @elseif($user->character_class === 3)
                            Discoverer
                        @else
                            None
                        @endif
                    </div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Last Active</div>
                    <div style="font-weight: 500;">
                        @if($user->time)
                            {{ \Carbon\Carbon::createFromTimestamp((int)$user->time)->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <a href="#account" class="tab active" data-tab="account">Account</a>
    <a href="#roles" class="tab" data-tab="roles">Roles & Permissions</a>
    <a href="#character" class="tab" data-tab="character">Character</a>
    <a href="#resources" class="tab" data-tab="resources">Resources</a>
    <a href="#planets" class="tab" data-tab="planets">Planets</a>
    <a href="#moderation" class="tab" data-tab="moderation">Moderation</a>
    <a href="#danger" class="tab" data-tab="danger">Danger Zone</a>
</div>

<!-- Account Tab -->
<div class="tab-content" id="account-tab">
    <div class="card">
        <div class="card-header">Account Information</div>
        
        <form id="accountForm" onsubmit="updateUser(event, 'account')">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" value="{{ $user->username }}" minlength="3" maxlength="20">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ $user->email }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                    <div class="form-help">Minimum 8 characters</div>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer; gap: 0.75rem;">
                        <input type="checkbox" name="vacation_mode" value="1" {{ $user->vacation_mode ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                        <span class="form-label" style="margin: 0;">Vacation Mode</span>
                    </label>
                    <div class="form-help">Protects from attacks, halts production</div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                Save Account Changes
            </button>
        </form>
    </div>
</div>

<!-- Roles Tab -->
<div class="tab-content" id="roles-tab" style="display: none;">
    <div class="card">
        <div class="card-header">Roles & Permissions</div>
        
        <form id="rolesForm" onsubmit="updateUser(event, 'roles')">
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <label style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: var(--bg-tertiary); border-radius: 0.75rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='var(--bg-elevated)'" onmouseout="this.style.background='var(--bg-tertiary)'">
                    <input type="checkbox" name="role_admin" value="1" {{ $user->hasRole('admin') ? 'checked' : '' }} style="width: 24px; height: 24px; cursor: pointer;">
                    <div>
                        <div style="font-weight: 600; font-size: 1.0625rem; margin-bottom: 0.25rem;">Administrator</div>
                        <div style="color: var(--text-muted); font-size: 0.875rem;">Full access to all admin features and settings</div>
                    </div>
                </label>
                
                <label style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: var(--bg-tertiary); border-radius: 0.75rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='var(--bg-elevated)'" onmouseout="this.style.background='var(--bg-tertiary)'">
                    <input type="checkbox" name="role_moderator" value="1" {{ $user->hasRole('moderator') ? 'checked' : '' }} style="width: 24px; height: 24px; cursor: pointer;">
                    <div>
                        <div style="font-weight: 600; font-size: 1.0625rem; margin-bottom: 0.25rem;">Moderator</div>
                        <div style="color: var(--text-muted); font-size: 0.875rem;">Can manage users and view reports</div>
                    </div>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem;">
                Update Roles
            </button>
        </form>
    </div>
</div>

<!-- Character Tab -->
<div class="tab-content" id="character-tab" style="display: none;">
    <!-- Current Class Display -->
    <div class="card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1)); border: 2px solid rgba(59, 130, 246, 0.3); margin-bottom: 2rem;">
        <div style="text-align: center;">
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Current Character Class</div>
            <div style="font-size: 2.5rem; font-weight: 900; font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #60a5fa, #a78bfa, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">
                @if($user->character_class === 1)
                    COLLECTOR
                @elseif($user->character_class === 2)
                    GENERAL
                @elseif($user->character_class === 3)
                    DISCOVERER
                @else
                    NONE
                @endif
            </div>
            @if($user->character_class_changed_at)
                <div style="color: var(--text-muted); font-size: 0.8125rem;">Selected {{ $user->character_class_changed_at->diffForHumans() }}</div>
            @endif
        </div>
    </div>
    
    <!-- Class Selection -->
    <div class="card">
        <div class="card-header">Change Character Class</div>
        
        <form id="characterForm" onsubmit="updateUser(event, 'character')">
            <div class="form-group">
                <label class="form-label">Select Class</label>
                <select name="character_class" class="form-input" id="classSelect" onchange="showClassBonuses(this.value)">
                    <option value="" {{ $user->character_class === null ? 'selected' : '' }}>None - No bonuses</option>
                    <option value="1" {{ $user->character_class === 1 ? 'selected' : '' }}>Collector - Resource Production Focus</option>
                    <option value="2" {{ $user->character_class === 2 ? 'selected' : '' }}>General - Military & Fleet Focus</option>
                    <option value="3" {{ $user->character_class === 3 ? 'selected' : '' }}>Discoverer - Research & Exploration Focus</option>
                </select>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1rem; background: var(--bg-tertiary); border-radius: 0.75rem;">
                    <input type="checkbox" name="reset_class_free_use" value="1" style="width: 20px; height: 20px; cursor: pointer;">
                    <div>
                        <div class="form-label" style="margin: 0;">Allow Free Class Change</div>
                        <div class="form-help" style="margin: 0;">Resets the free class selection flag (normally costs 500k DM)</div>
                    </div>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Update Character Class
            </button>
        </form>
    </div>
    
    <!-- Class Bonuses Info -->
    <div class="card" id="collectorBonuses" style="display: {{ $user->character_class === 1 ? 'block' : 'none' }};">
        <div class="card-header" style="background: linear-gradient(135deg, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Collector Bonuses</div>
        <div style="display: grid; gap: 1rem;">
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">⛏️ Mine Production +25%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Metal, Crystal, and Deuterium mines produce 25% more resources</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">⚡ Energy Production +10%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Solar plants and fusion reactors produce 10% more energy</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">🚚 Transporter Speed +100%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Small and Large Cargo ships fly twice as fast</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">📦 Transporter Cargo +25%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Cargo ships can carry 25% more resources</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">🤖 Crawler Bonus +50%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Crawlers provide 50% more production bonus</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">📈 Max Crawler Overload 150%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can set crawler production up to 150% instead of 100%</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">💎 Building Speedup -10% DM Cost</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Dark matter speedups for buildings cost 10% less</div>
            </div>
            <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--success);">🎁 Exclusive Ship: Crawler</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can build Crawler units for production bonuses</div>
            </div>
        </div>
    </div>
    
    <div class="card" id="generalBonuses" style="display: {{ $user->character_class === 2 ? 'block' : 'none' }};">
        <div class="card-header" style="background: linear-gradient(135deg, #ef4444, #f87171); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">General Bonuses</div>
        <div style="display: grid; gap: 1rem;">
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">⚔️ Combat Ship Speed +100%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">All military ships fly twice as fast</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">♻️ Recycler Speed +100%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Recyclers fly twice as fast for debris collection</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">⛽ Deuterium Consumption -50%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">All fleet missions use 50% less deuterium fuel</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">📦 Recycler/Pathfinder Cargo +20%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Recyclers and Pathfinders carry 20% more cargo</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">🔬 Combat Research +2 Levels</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Weapons, Shielding, and Armor count as 2 levels higher in combat</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">🚀 Fleet Slots +2</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can have 2 additional fleet missions active</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">🌙 Moon Fields +5</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">All moons have 5 additional building fields</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">💎 Shipyard Speedup -10% DM Cost</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Dark matter speedups for ships cost 10% less</div>
            </div>
            <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--danger);">🎁 Exclusive Ship: Reaper</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can build Reaper ships that auto-collect 30% of debris after attacks</div>
            </div>
        </div>
    </div>
    
    <div class="card" id="discovererBonuses" style="display: {{ $user->character_class === 3 ? 'block' : 'none' }};">
        <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Discoverer Bonuses</div>
        <div style="display: grid; gap: 1rem;">
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">🔬 Research Time -25%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">All research completes 25% faster</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">🚀 Expedition Slots +2</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can send 2 additional expeditions simultaneously</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">💰 Expedition Resources +50%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Expeditions find 50% more resources</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">👾 Expedition Enemies -50%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">50% less chance of encountering pirates or aliens</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">🌍 Planet Size +10%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">New colonies have 10% more building fields</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">📡 Phalanx Range +20%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Sensor phalanx can scan 20% further</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">🎯 Inactive Loot 75%</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can loot 75% from inactive players (vs 50% default)</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">👁️ Expedition Debris Visible</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can see debris fields at expedition positions</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">💎 Research Speedup -10% DM Cost</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Dark matter speedups for research cost 10% less</div>
            </div>
            <div style="padding: 1rem; background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem;">
                <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary);">🎁 Exclusive Ship: Pathfinder</div>
                <div style="color: var(--text-secondary); font-size: 0.875rem;">Can build Pathfinder ships for enhanced exploration</div>
            </div>
        </div>
    </div>
</div>

<!-- Resources Tab -->
<div class="tab-content" id="resources-tab" style="display: none;">
    <!-- Dark Matter Section -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1)); border: 2px solid rgba(59, 130, 246, 0.3);">
            <div style="text-align: center;">
                <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Dark Matter Balance</div>
                <div id="dmBalance" style="font-size: 3.5rem; font-weight: 900; font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #60a5fa, #a78bfa, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">
                    {{ number_format($user->dark_matter) }}
                </div>
                <div style="color: var(--text-muted); font-size: 0.8125rem;">Available for use</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Add/Subtract Dark Matter</div>
            
            <form id="darkMatterForm" onsubmit="updateDarkMatter(event)">
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <div style="display: flex; gap: 0.75rem;">
                        <button type="button" class="btn btn-secondary" onclick="setDMAmount(-10000)" style="flex: 1;">-10k</button>
                        <button type="button" class="btn btn-secondary" onclick="setDMAmount(-1000)" style="flex: 1;">-1k</button>
                        <button type="button" class="btn btn-secondary" onclick="setDMAmount(1000)" style="flex: 1;">+1k</button>
                        <button type="button" class="btn btn-secondary" onclick="setDMAmount(10000)" style="flex: 1;">+10k</button>
                    </div>
                    <input type="number" name="dm_amount" id="dmAmountInput" class="form-input" value="1000" step="100" style="margin-top: 0.75rem;">
                    <div class="form-help">Use negative values to subtract, positive to add</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Transaction Description</label>
                    <input type="text" name="dm_description" class="form-input" value="Admin adjustment" maxlength="255">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Apply Transaction
                </button>
            </form>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="card">
        <div class="card-header">Transaction History (Last 20)</div>
        
        @if($dmTransactions->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Balance After</th>
                        </tr>
                    </thead>
                    <tbody id="transactionHistory">
                        @foreach($dmTransactions as $transaction)
                        <tr>
                            <td style="color: var(--text-secondary); font-size: 0.875rem;">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
                            </td>
                            <td>
                                <span class="badge {{ $transaction->amount > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $transaction->type }}
                                </span>
                            </td>
                            <td style="font-family: monospace; font-weight: 600; color: {{ $transaction->amount > 0 ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                            </td>
                            <td style="color: var(--text-secondary);">{{ $transaction->description }}</td>
                            <td style="font-family: monospace; font-weight: 600; color: var(--primary);">
                                {{ number_format($transaction->balance_after) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                No transactions yet
            </div>
        @endif
    </div>
    
    <!-- Planet Resources -->
    <div class="card">
        <div class="card-header">Planet Resources Management</div>
        
        @if($planets->count() > 0)
            <div style="display: grid; gap: 1.5rem;">
                @foreach($planets as $planet)
                    <div style="background: linear-gradient(135deg, var(--bg-tertiary), var(--bg-elevated)); border: 2px solid var(--border); border-radius: 1.25rem; padding: 2rem; transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(59, 130, 246, 0.4)'" onmouseout="this.style.borderColor='var(--border)'">
                        <!-- Planet Header -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
                            <div>
                                <h4 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">{{ $planet->name }}</h4>
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <span style="font-family: monospace; color: var(--primary); font-weight: 600;">{{ $planet->galaxy }}:{{ $planet->system }}:{{ $planet->planet }}</span>
                                    <span class="badge {{ $planet->planet_type == 1 ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $planet->planet_type == 1 ? 'Planet' : 'Moon' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Resources Display -->
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 1.5rem;">
                            <div style="background: rgba(107, 114, 128, 0.15); border: 1px solid rgba(107, 114, 128, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                                <div style="color: #9ca3af; font-size: 0.8125rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Metal</div>
                                <div class="planet-{{ $planet->id }}-metal" style="font-size: 1.75rem; font-weight: 700; font-family: monospace; color: #d1d5db;">{{ number_format($planet->metal) }}</div>
                            </div>
                            <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                                <div style="color: #60a5fa; font-size: 0.8125rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Crystal</div>
                                <div class="planet-{{ $planet->id }}-crystal" style="font-size: 1.75rem; font-weight: 700; font-family: monospace; color: #60a5fa;">{{ number_format($planet->crystal) }}</div>
                            </div>
                            <div style="background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                                <div style="color: #a78bfa; font-size: 0.8125rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Deuterium</div>
                                <div class="planet-{{ $planet->id }}-deuterium" style="font-size: 1.75rem; font-weight: 700; font-family: monospace; color: #a78bfa;">{{ number_format($planet->deuterium) }}</div>
                            </div>
                        </div>
                        
                        <!-- Add Resources Form -->
                        <form onsubmit="updatePlanetResources(event, {{ $planet->id }}, {{ $planet->galaxy }}, {{ $planet->system }}, {{ $planet->planet }})">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.25rem;">
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Add Metal</label>
                                    <input type="number" name="metal" class="form-input" value="0" step="10000" placeholder="0">
                                </div>
                                
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Add Crystal</label>
                                    <input type="number" name="crystal" class="form-input" value="0" step="10000" placeholder="0">
                                </div>
                                
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Add Deuterium</label>
                                    <input type="number" name="deuterium" class="form-input" value="0" step="5000" placeholder="0">
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 0.75rem;">
                                <button type="button" class="btn btn-secondary" onclick="setPlanetResources(this.form, 1000000, 1000000, 500000)" style="flex: 1;">+1M / 1M / 500k</button>
                                <button type="button" class="btn btn-secondary" onclick="setPlanetResources(this.form, 10000000, 10000000, 5000000)" style="flex: 1;">+10M / 10M / 5M</button>
                                <button type="submit" class="btn btn-primary" style="flex: 2;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add to {{ $planet->name }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p style="font-size: 1.125rem; font-weight: 500;">No planets</p>
            </div>
        @endif
    </div>
</div>

<!-- Planets Tab -->
<div class="tab-content" id="planets-tab" style="display: none;">
    @if($planets->count() > 0)
        @foreach($planets as $planet)
            <div class="card" style="margin-bottom: 1.5rem;">
                <!-- Planet Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid var(--border);">
                    <div>
                        <h3 style="font-weight: 700; font-size: 1.5rem; margin-bottom: 0.75rem;">{{ $planet->name }}</h3>
                        <div style="display: flex; gap: 1.25rem; align-items: center;">
                            <span style="font-family: monospace; color: var(--primary); font-weight: 700; font-size: 1.125rem;">[{{ $planet->galaxy }}:{{ $planet->system }}:{{ $planet->planet }}]</span>
                            <span class="badge {{ $planet->planet_type == 1 ? 'badge-primary' : 'badge-secondary' }}" style="font-size: 0.875rem;">
                                {{ $planet->planet_type == 1 ? 'Planet' : 'Moon' }}
                            </span>
                            <span class="badge badge-primary" style="font-size: 0.875rem;">Diameter: {{ number_format($planet->diameter) }} km</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.5rem;">Building Fields</div>
                        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary);">{{ $planet->field_current }}/{{ $planet->field_max }}</div>
                    </div>
                </div>
                
                <!-- Resources Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="background: rgba(107, 114, 128, 0.15); border: 1px solid rgba(107, 114, 128, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                        <div style="color: #9ca3af; font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Metal</div>
                        <div style="font-size: 1.5rem; font-weight: 700; font-family: monospace; color: #d1d5db;">{{ number_format($planet->metal) }}</div>
                    </div>
                    <div style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                        <div style="color: #60a5fa; font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Crystal</div>
                        <div style="font-size: 1.5rem; font-weight: 700; font-family: monospace; color: #60a5fa;">{{ number_format($planet->crystal) }}</div>
                    </div>
                    <div style="background: rgba(139, 92, 246, 0.15); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                        <div style="color: #a78bfa; font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Deuterium</div>
                        <div style="font-size: 1.5rem; font-weight: 700; font-family: monospace; color: #a78bfa;">{{ number_format($planet->deuterium) }}</div>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 0.75rem; padding: 1.25rem; text-align: center;">
                        <div style="color: #fbbf24; font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Energy</div>
                        <div style="font-size: 1.5rem; font-weight: 700; font-family: monospace; color: {{ $planet->energy_max >= $planet->energy_used ? '#34d399' : '#f87171' }};">{{ number_format($planet->energy_max - $planet->energy_used) }}</div>
                    </div>
                </div>
                
                <!-- Planet Details -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                    <div>
                        <div style="font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">Environment</div>
                        <div style="display: grid; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem;">
                                <span style="color: var(--text-muted);">Temperature</span>
                                <span style="font-weight: 600;">{{ $planet->temp_min }}°C to {{ $planet->temp_max }}°C</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem;">
                                <span style="color: var(--text-muted);">Diameter</span>
                                <span style="font-weight: 600;">{{ number_format($planet->diameter) }} km</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem;">
                                <span style="color: var(--text-muted);">Last Updated</span>
                                <span style="font-weight: 600;">{{ \Carbon\Carbon::createFromTimestamp($planet->time_last_update)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">Production</div>
                        <div style="display: grid; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem;">
                                <span style="color: var(--text-muted);">Metal/Hour</span>
                                <span style="font-weight: 600; color: #9ca3af;">+{{ number_format($planet->metal_production) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem;">
                                <span style="color: var(--text-muted);">Crystal/Hour</span>
                                <span style="font-weight: 600; color: #60a5fa;">+{{ number_format($planet->crystal_production) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem;">
                                <span style="color: var(--text-muted);">Deuterium/Hour</span>
                                <span style="font-weight: 600; color: #a78bfa;">+{{ number_format($planet->deuterium_production) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1.5rem; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p style="font-size: 1.125rem; font-weight: 500;">No planets</p>
            </div>
        </div>
    @endif
</div>

<!-- Moderation Tab -->
<div class="tab-content" id="moderation-tab" style="display: none;">
    @if($user->is_banned)
        <!-- Banned Status Card -->
        <div class="card" style="border-color: rgba(239, 68, 68, 0.5); background: linear-gradient(135deg, var(--bg-secondary), rgba(239, 68, 68, 0.08));">
            <div class="card-header" style="color: var(--danger); display: flex; align-items: center; gap: 0.75rem;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                User is Currently Banned
            </div>
            
            <div style="display: grid; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border-radius: 0.75rem;">
                    <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Ban Reason</div>
                    <div style="font-weight: 500; color: var(--danger);">{{ $user->ban_reason ?? 'No reason provided' }}</div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="padding: 1rem; background: var(--bg-tertiary); border-radius: 0.75rem;">
                        <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Banned At</div>
                        <div style="font-weight: 500;">{{ $user->banned_at ? $user->banned_at->format('M d, Y H:i') : 'N/A' }}</div>
                    </div>
                    
                    <div style="padding: 1rem; background: var(--bg-tertiary); border-radius: 0.75rem;">
                        <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Ban Expires</div>
                        <div style="font-weight: 500;">{{ $user->banned_until ? $user->banned_until->format('M d, Y H:i') : 'Permanent' }}</div>
                    </div>
                </div>
                
                @if($user->banned_by_user_id)
                    <div style="padding: 1rem; background: var(--bg-tertiary); border-radius: 0.75rem;">
                        <div style="color: var(--text-muted); font-size: 0.8125rem; margin-bottom: 0.25rem;">Banned By</div>
                        <div style="font-weight: 500;">Admin #{{ $user->banned_by_user_id }}</div>
                    </div>
                @endif
            </div>
            
            <form action="{{ route('admin.users.unban', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" style="width: 100%;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Unban User
                </button>
            </form>
        </div>
    @else
        <!-- Ban User Card -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 0.75rem;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Ban User
            </div>
            
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                Banning a user will prevent them from logging in and accessing the game. You can set a temporary ban with an expiration date or ban them permanently.
            </p>
            
            <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Ban Reason <span style="color: var(--danger);">*</span></label>
                    <textarea name="ban_reason" class="form-input" rows="3" placeholder="Enter reason for ban (visible to other admins)" required style="resize: vertical;"></textarea>
                    <div class="form-help">This will be visible to other administrators</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ban Duration <span style="color: var(--danger);">*</span></label>
                    <select name="ban_duration" class="form-input" required>
                        <option value="">Select duration...</option>
                        <option value="1day">1 Day</option>
                        <option value="3days">3 Days</option>
                        <option value="7days">7 Days (1 Week)</option>
                        <option value="30days">30 Days (1 Month)</option>
                        <option value="permanent">Permanent</option>
                    </select>
                </div>
                
                <div style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 0.75rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; color: var(--danger);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <div style="font-weight: 600; color: var(--danger); margin-bottom: 0.25rem;">Warning</div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                The user will be immediately logged out and unable to access their account until the ban is lifted.
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-danger" style="width: 100%;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    Ban User
                </button>
            </form>
        </div>
    @endif
</div>

<!-- Danger Zone Tab -->
<div class="tab-content" id="danger-tab" style="display: none;">
    <div class="card" style="border-color: rgba(239, 68, 68, 0.3); background: linear-gradient(135deg, var(--bg-secondary), rgba(239, 68, 68, 0.05));">
        <div class="card-header" style="color: var(--danger);">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Danger Zone
        </div>
        
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            Permanently delete this user and <strong>all related data</strong> including: planets, fleet missions, messages, notes, alliance memberships, battle reports, resources, technologies, and more. This action cannot be undone.
        </p>
        
        <button onclick="deleteUser({{ $user->id }}, '{{ $user->username }}')" class="btn btn-danger" style="width: 100%;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete User Permanently
        </button>
    </div>
</div>

@push('scripts')
<script>
function setDMAmount(amount) {
    const input = document.getElementById('dmAmountInput');
    if (input) {
        input.value = amount;
    }
}

function showClassBonuses(classValue) {
    // Hide all bonus cards
    document.getElementById('collectorBonuses').style.display = 'none';
    document.getElementById('generalBonuses').style.display = 'none';
    document.getElementById('discovererBonuses').style.display = 'none';
    
    // Show selected class bonuses
    if (classValue === '1') {
        document.getElementById('collectorBonuses').style.display = 'block';
    } else if (classValue === '2') {
        document.getElementById('generalBonuses').style.display = 'block';
    } else if (classValue === '3') {
        document.getElementById('discovererBonuses').style.display = 'block';
    }
}

async function updateDarkMatter(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    formData.append('action', 'add_dark_matter');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
    
    try {
        const response = await fetch('{{ route('admin.users.update', $user->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            Toast.show(data.message || 'Dark matter updated successfully', 'success');
            
            // Update balance display
            const balanceDisplay = document.getElementById('dmBalance');
            if (balanceDisplay && data.new_balance !== undefined) {
                balanceDisplay.textContent = data.new_balance.toLocaleString();
            }
            
            // Add new transaction to history
            const historyTable = document.getElementById('transactionHistory');
            if (historyTable && data.amount) {
                const now = new Date();
                const dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' ' + now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
                const description = formData.get('dm_description');
                const badgeClass = data.amount > 0 ? 'badge-success' : 'badge-danger';
                const amountColor = data.amount > 0 ? 'var(--success)' : 'var(--danger)';
                
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td style="color: var(--text-secondary); font-size: 0.875rem;">${dateStr}</td>
                    <td><span class="badge ${badgeClass}">admin_adjustment</span></td>
                    <td style="font-family: monospace; font-weight: 600; color: ${amountColor};">${data.amount > 0 ? '+' : ''}${data.amount.toLocaleString()}</td>
                    <td style="color: var(--text-secondary);">${description}</td>
                    <td style="font-family: monospace; font-weight: 600; color: var(--primary);">${data.new_balance.toLocaleString()}</td>
                `;
                historyTable.insertBefore(newRow, historyTable.firstChild);
            }
            
            // Reset form
            form.querySelector('input[name="dm_amount"]').value = '1000';
            form.querySelector('input[name="dm_description"]').value = 'Admin adjustment';
        } else {
            throw new Error(data.message || 'Update failed');
        }
    } catch (error) {
        Toast.show(error.message || 'Failed to update dark matter', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }
}

async function updateUser(event, action) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    formData.append('action', `update_${action}`);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Saving...';
    
    try {
        const response = await fetch('{{ route('admin.users.update', $user->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            Toast.show('User updated successfully', 'success');
            
            // Update UI directly based on action
            if (action === 'dark_matter') {
                const dmAmount = parseInt(formData.get('dm_amount'));
                const balanceDisplay = document.getElementById('dmBalance');
                if (balanceDisplay) {
                    const currentText = balanceDisplay.textContent.replace(/,/g, '');
                    const currentBalance = parseInt(currentText) || {{ $user->dark_matter }};
                    const newBalance = Math.max(0, currentBalance + dmAmount);
                    balanceDisplay.textContent = newBalance.toLocaleString();
                    
                    // Add new transaction to history table
                    const historyTable = document.getElementById('transactionHistory');
                    if (historyTable) {
                        const now = new Date();
                        const dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' ' + now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                        const description = formData.get('dm_description');
                        const badgeClass = dmAmount > 0 ? 'badge-success' : 'badge-danger';
                        const amountColor = dmAmount > 0 ? 'var(--success)' : 'var(--danger)';
                        
                        const newRow = `
                            <tr>
                                <td style="color: var(--text-secondary); font-size: 0.875rem;">${dateStr}</td>
                                <td><span class="badge ${badgeClass}">admin_adjustment</span></td>
                                <td style="font-family: monospace; font-weight: 600; color: ${amountColor};">${dmAmount > 0 ? '+' : ''}${dmAmount.toLocaleString()}</td>
                                <td style="color: var(--text-secondary);">${description}</td>
                                <td style="font-family: monospace; font-weight: 600; color: var(--primary);">${newBalance.toLocaleString()}</td>
                            </tr>
                        `;
                        historyTable.insertAdjacentHTML('afterbegin', newRow);
                    }
                }
                
                // Reset form
                form.querySelector('input[name="dm_amount"]').value = '1000';
                form.querySelector('input[name="dm_description"]').value = 'Admin adjustment';
            }
            
            // Don't reload page
        } else {
            throw new Error('Update failed');
        }
    } catch (error) {
        Toast.show('Failed to update user', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }
}

function setPlanetResources(form, metal, crystal, deuterium) {
    form.querySelector('input[name="metal"]').value = metal;
    form.querySelector('input[name="crystal"]').value = crystal;
    form.querySelector('input[name="deuterium"]').value = deuterium;
}

async function updatePlanetResources(event, planetId, galaxy, system, position) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHTML = submitBtn.innerHTML;
    
    formData.append('galaxy', galaxy);
    formData.append('system', system);
    formData.append('position', position);
    formData.append('update_resources_planet', '1');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Adding...';
    
    try {
        const response = await fetch('/admin/developer-tools/resources', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            Toast.show('Resources added successfully', 'success');
            
            // Get amounts from form
            const metal = parseInt(formData.get('metal')) || 0;
            const crystal = parseInt(formData.get('crystal')) || 0;
            const deuterium = parseInt(formData.get('deuterium')) || 0;
            
            // Update resource displays
            const metalDisplay = document.querySelector(`.planet-${planetId}-metal`);
            const crystalDisplay = document.querySelector(`.planet-${planetId}-crystal`);
            const deuteriumDisplay = document.querySelector(`.planet-${planetId}-deuterium`);
            
            if (metalDisplay) {
                const current = parseInt(metalDisplay.textContent.replace(/,/g, ''));
                metalDisplay.textContent = (current + metal).toLocaleString();
            }
            if (crystalDisplay) {
                const current = parseInt(crystalDisplay.textContent.replace(/,/g, ''));
                crystalDisplay.textContent = (current + crystal).toLocaleString();
            }
            if (deuteriumDisplay) {
                const current = parseInt(deuteriumDisplay.textContent.replace(/,/g, ''));
                deuteriumDisplay.textContent = (current + deuterium).toLocaleString();
            }
            
            // Reset form inputs
            form.querySelectorAll('input[type="number"]').forEach(input => {
                input.value = '0';
            });
        } else {
            throw new Error('Update failed');
        }
    } catch (error) {
        Toast.show('Failed to update resources', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }
}

async function deleteUser(id, username) {
    if (!confirm(`Delete user "${username}"? This will permanently delete ALL related data including planets, fleets, messages, alliances, and more. This cannot be undone!`)) return;
    
    const secondConfirm = prompt(`Type "${username}" to confirm deletion:`);
    if (secondConfirm !== username) {
        Toast.show('Deletion cancelled - name did not match', 'warning');
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch(`/admin/users/${id}/delete`, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            Toast.show('User deleted successfully', 'success');
            setTimeout(() => window.location.href = '{{ route('admin.users.index') }}', 1500);
        } else {
            throw new Error('Delete failed');
        }
    } catch (error) {
        Toast.show('Failed to delete user', 'error');
    }
}
</script>
@endpush
@endsection

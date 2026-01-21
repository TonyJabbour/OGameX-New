@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-description">Manage your OGameX server settings and developer tools</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Universe Name</div>
        <div class="stat-value">{{ $universe_name }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Economy Speed</div>
        <div class="stat-value">{{ $economy_speed }}x</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Fleet Speed</div>
        <div class="stat-value">{{ $fleet_speed_war }}x</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Battle Engine</div>
        <div class="stat-value" style="text-transform: uppercase; font-size: 2rem;">{{ $battle_engine }}</div>
    </div>
</div>

<!-- Quick Access Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
    <a href="{{ route('admin.serversettings.index') }}" style="text-decoration: none;">
        <div class="card" style="cursor: pointer; height: 100%; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 24px rgba(59, 130, 246, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 1rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);">
                    <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.625rem; color: var(--text-primary);">Server Settings</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9375rem; line-height: 1.5;">Configure universe, economy, battle, and player settings</p>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.users.index') }}" style="text-decoration: none;">
        <div class="card" style="cursor: pointer; height: 100%; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 24px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #10b981, #34d399); border-radius: 1rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);">
                    <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.625rem; color: var(--text-primary);">User Management</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9375rem; line-height: 1.5;">Manage players, roles, and permissions</p>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.images.index') }}" style="text-decoration: none;">
        <div class="card" style="cursor: pointer; height: 100%; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 24px rgba(245, 158, 11, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #f59e0b, #fbbf24); border-radius: 1rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);">
                    <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.625rem; color: var(--text-primary);">Image Management</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9375rem; line-height: 1.5;">Upload and organize game content images</p>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.developershortcuts.index') }}" style="text-decoration: none;">
        <div class="card" style="cursor: pointer; height: 100%; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 0 24px rgba(139, 92, 246, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #8b5cf6, #ec4899); border-radius: 1rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(139, 92, 246, 0.3);">
                    <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.625rem; color: var(--text-primary);">Developer Tools</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9375rem; line-height: 1.5;">Quick actions and testing utilities</p>
                </div>
            </div>
        </div>
    </a>
</div>
@endsection

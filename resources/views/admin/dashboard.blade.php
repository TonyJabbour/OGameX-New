@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-description">Manage your OGameX server settings and developer tools</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Server Settings Card -->
    <div class="card" style="cursor: pointer;" onclick="window.location='{{ route('admin.serversettings.index') }}'">
        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 style="font-weight: 600; font-size: 1.125rem;">Server Settings</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Configure game parameters</p>
            </div>
        </div>
        <p style="color: var(--text-secondary); font-size: 0.875rem;">Manage universe, economy, battle, and player settings</p>
    </div>

    <!-- Developer Tools Card -->
    <div class="card" style="cursor: pointer;" onclick="window.location='{{ route('admin.developershortcuts.index') }}'">
        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6, #ec4899); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
            </div>
            <div>
                <h3 style="font-weight: 600; font-size: 1.125rem;">Developer Tools</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Testing and debugging</p>
            </div>
        </div>
        <p style="color: var(--text-secondary); font-size: 0.875rem;">Quick actions, resource management, and testing utilities</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="card">
    <div class="card-header">Server Overview</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Universe Name</div>
            <div style="font-size: 1.5rem; font-weight: 600;">{{ $universe_name }}</div>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Economy Speed</div>
            <div style="font-size: 1.5rem; font-weight: 600;">{{ $economy_speed }}x</div>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Fleet Speed</div>
            <div style="font-size: 1.5rem; font-weight: 600;">{{ $fleet_speed_war }}x</div>
        </div>
        <div>
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.25rem;">Battle Engine</div>
            <div style="font-size: 1.5rem; font-weight: 600; text-transform: uppercase;">{{ $battle_engine }}</div>
        </div>
    </div>
</div>
@endsection

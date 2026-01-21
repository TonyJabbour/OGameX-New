@extends('admin.layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">User Management</h1>
    <p class="page-description">Manage players and their accounts</p>
</div>

@if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('error') }}
    </div>
@endif

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="card">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">Total Users</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--primary);">{{ $stats['total'] }}</div>
    </div>
    <div class="card">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">Active (7 days)</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--success);">{{ $stats['active'] }}</div>
    </div>
    <div class="card">
        <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">On Vacation</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--warning);">{{ $stats['vacation'] }}</div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div style="display: grid; grid-template-columns: 1fr auto auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Search Users</label>
                <input type="text" name="search" class="form-input" placeholder="Username or email..." value="{{ $search }}">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Filter</label>
                <select name="filter" class="form-input">
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Users</option>
                    <option value="active" {{ $filter == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filter == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="vacation" {{ $filter == 'vacation' ? 'selected' : '' }}>On Vacation</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">Players ({{ $users->total() }})</div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">ID</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Username</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Email</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Dark Matter</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Status</th>
                    <th style="padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Last Active</th>
                    <th style="padding: 1rem; text-align: right; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.1);">
                    <td style="padding: 1rem; color: var(--text-secondary);">#{{ $user->id }}</td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $user->username }}</div>
                        @if($user->hasRole('admin'))
                            <span style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #1e293b; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">ADMIN</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $user->email }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ number_format($user->dark_matter) }}</td>
                    <td style="padding: 1rem;">
                        @if($user->vacation_mode)
                            <span style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;">Vacation</span>
                        @elseif($user->isOnline())
                            <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;">Online</span>
                        @else
                            <span style="background: rgba(100, 116, 139, 0.2); color: #94a3b8; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem;">Offline</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; color: var(--text-secondary);">
                        @if($user->time)
                            {{ \Carbon\Carbon::createFromTimestamp((int)$user->time)->diffForHumans() }}
                        @else
                            Never
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-muted);">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
        <div style="padding: 1rem; border-top: 1px solid var(--border); display: flex; justify-content: center;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

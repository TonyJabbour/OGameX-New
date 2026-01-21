@extends('admin.layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">User Management</h1>
    <p class="page-description">Manage players and their accounts</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Active (7 days)</div>
        <div class="stat-value">{{ $stats['active'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">On Vacation</div>
        <div class="stat-value">{{ $stats['vacation'] }}</div>
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
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Dark Matter</th>
                    <th>Status</th>
                    <th>Last Active</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><span style="color: var(--text-muted); font-family: monospace;">#{{ $user->id }}</span></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem;">
                                {{ substr($user->username, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $user->username }}</div>
                                @if($user->hasRole('admin'))
                                    <span class="badge" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #1e293b; border: none; font-size: 0.6875rem;">ADMIN</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color: var(--text-secondary);">{{ $user->email }}</td>
                    <td>
                        <span style="font-family: monospace; color: var(--primary); font-weight: 600;">{{ number_format($user->dark_matter) }}</span>
                    </td>
                    <td>
                        @if($user->vacation_mode)
                            <span class="badge badge-warning">Vacation</span>
                        @elseif($user->isOnline())
                            <span class="badge badge-success">Online</span>
                        @else
                            <span class="badge" style="background: rgba(100, 116, 139, 0.15); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3);">Offline</span>
                        @endif
                    </td>
                    <td style="color: var(--text-secondary); font-size: 0.875rem;">
                        @if($user->time)
                            {{ \Carbon\Carbon::createFromTimestamp((int)$user->time)->diffForHumans() }}
                        @else
                            <span style="color: var(--text-muted);">Never</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary" style="padding: 0.625rem 1.25rem; font-size: 0.875rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-muted);">No users found</td>
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

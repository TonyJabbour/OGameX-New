@extends('admin.layouts.admin')

@section('title', 'User Details - ' . $user->username)

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $user->username }}</h1>
    <p class="page-description">User ID: #{{ $user->id }}</p>
</div>

@if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Left Column -->
    <div>
        <!-- Account Information -->
        <div class="card">
            <div class="card-header">Account Information</div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" value="{{ $user->username }}" minlength="3" maxlength="20">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ $user->email }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter new password">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Dark Matter</label>
                    <input type="number" name="dark_matter" class="form-input" value="{{ $user->dark_matter }}" min="0">
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="vacation_mode" value="1" {{ $user->vacation_mode ? 'checked' : '' }} style="margin-right: 0.5rem;">
                        <span class="form-label" style="margin: 0;">Vacation Mode</span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
        
        <!-- Roles & Permissions -->
        <div class="card">
            <div class="card-header">Roles & Permissions</div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="update_roles">
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="role_admin" value="1" {{ $user->hasRole('admin') ? 'checked' : '' }} style="margin-right: 0.5rem;">
                        <span class="form-label" style="margin: 0;">Admin</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="role_moderator" value="1" {{ $user->hasRole('moderator') ? 'checked' : '' }} style="margin-right: 0.5rem;">
                        <span class="form-label" style="margin: 0;">Moderator</span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Roles</button>
            </form>
        </div>
        
        <!-- Character Class Management -->
        <div class="card">
            <div class="card-header">Character Class</div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="update_class">
                
                <div class="form-group">
                    <label class="form-label">Character Class</label>
                    <select name="character_class" class="form-input">
                        <option value="" {{ $user->character_class === null ? 'selected' : '' }}>None</option>
                        <option value="1" {{ $user->character_class === 1 ? 'selected' : '' }}>Collector</option>
                        <option value="2" {{ $user->character_class === 2 ? 'selected' : '' }}>General</option>
                        <option value="3" {{ $user->character_class === 3 ? 'selected' : '' }}>Discoverer</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="reset_class_free_use" value="1" style="margin-right: 0.5rem;">
                        <span class="form-label" style="margin: 0;">Allow Free Class Change</span>
                    </label>
                    <div class="form-help">Reset the free class selection flag</div>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Class</button>
            </form>
        </div>
        
        <!-- Dark Matter Transactions -->
        <div class="card">
            <div class="card-header">Dark Matter Transactions</div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="add_dark_matter">
                
                <div class="form-group">
                    <label class="form-label">Add/Subtract Dark Matter</label>
                    <input type="number" name="dm_amount" class="form-input" value="1000" step="100">
                    <div class="form-help">Use negative values to subtract. Current: {{ number_format($user->dark_matter) }}</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Transaction Description</label>
                    <input type="text" name="dm_description" class="form-input" value="Admin adjustment" maxlength="255">
                </div>
                
                <button type="submit" class="btn btn-primary">Apply Transaction</button>
            </form>
        </div>
        
        <!-- Planets -->
        <div class="card">
            <div class="card-header">Planets ({{ $planets->count() }})</div>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <th style="padding: 0.75rem; text-align: left; color: var(--text-muted); font-size: 0.875rem;">Name</th>
                            <th style="padding: 0.75rem; text-align: left; color: var(--text-muted); font-size: 0.875rem;">Coordinates</th>
                            <th style="padding: 0.75rem; text-align: left; color: var(--text-muted); font-size: 0.875rem;">Type</th>
                            <th style="padding: 0.75rem; text-align: right; color: var(--text-muted); font-size: 0.875rem;">Fields</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($planets as $planet)
                        <tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.1);">
                            <td style="padding: 0.75rem; font-weight: 500;">{{ $planet->name }}</td>
                            <td style="padding: 0.75rem; color: var(--text-secondary);">{{ $planet->galaxy }}:{{ $planet->system }}:{{ $planet->planet }}</td>
                            <td style="padding: 0.75rem;">
                                @if($planet->planet_type == 1)
                                    <span style="color: #60a5fa;">Planet</span>
                                @else
                                    <span style="color: #a78bfa;">Moon</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; text-align: right; color: var(--text-secondary);">{{ $planet->field_current }}/{{ $planet->field_max }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding: 1.5rem; text-align: center; color: var(--text-muted);">No planets</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div>
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">Quick Stats</div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Character Class</div>
                    <div style="font-weight: 600; color: var(--text-primary);">
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
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Alliance</div>
                    <div style="font-weight: 600; color: var(--text-primary);">
                        @if($user->alliance_id)
                            {{ $user->alliance->tag ?? 'Unknown' }}
                        @else
                            None
                        @endif
                    </div>
                </div>
                
                <div>
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Registered</div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ $user->created_at->diffForHumans() }}</div>
                </div>
                
                <div>
                    <div style="color: var(--text-muted); font-size: 0.875rem;">Last IP</div>
                    <div style="font-weight: 600; color: var(--text-primary); font-family: monospace;">{{ $user->last_ip ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Danger Zone -->
        <div class="card" style="border-color: rgba(239, 68, 68, 0.3);">
            <div class="card-header" style="color: var(--danger);">Danger Zone</div>
            
            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!');">
                @csrf
                <p style="color: var(--text-secondary); margin-bottom: 1rem; font-size: 0.875rem;">Permanently delete this user and all their planets.</p>
                <button type="submit" class="btn" style="background: var(--danger); color: white; width: 100%;">Delete User</button>
            </form>
        </div>
    </div>
</div>

<div style="margin-top: 1.5rem;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back to Users</a>
</div>
@endsection

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
    <div class="card">
        <div class="card-header">Character Class</div>
        
        <form id="characterForm" onsubmit="updateUser(event, 'character')">
            <div class="form-group">
                <label class="form-label">Character Class</label>
                <select name="character_class" class="form-input">
                    <option value="" {{ $user->character_class === null ? 'selected' : '' }}>None</option>
                    <option value="1" {{ $user->character_class === 1 ? 'selected' : '' }}>Collector (+25% Production)</option>
                    <option value="2" {{ $user->character_class === 2 ? 'selected' : '' }}>General (+10% Fleet Capacity)</option>
                    <option value="3" {{ $user->character_class === 3 ? 'selected' : '' }}>Discoverer (+10% Research Speed)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="reset_class_free_use" value="1" style="width: 20px; height: 20px; cursor: pointer;">
                    <span class="form-label" style="margin: 0;">Allow Free Class Change</span>
                </label>
                <div class="form-help">Resets the free class selection flag</div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Update Character Class
            </button>
        </form>
    </div>
</div>

<!-- Resources Tab -->
<div class="tab-content" id="resources-tab" style="display: none;">
    <div class="card">
        <div class="card-header">Dark Matter Management</div>
        
        <div style="background: var(--bg-tertiary); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">Current Balance</div>
            <div style="font-size: 3rem; font-weight: 800; font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                {{ number_format($user->dark_matter) }}
            </div>
        </div>
        
        <form id="darkMatterForm" onsubmit="updateUser(event, 'dark_matter')">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" name="dm_amount" class="form-input" value="1000" step="100">
                    <div class="form-help">Use negative values to subtract</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Transaction Description</label>
                    <input type="text" name="dm_description" class="form-input" value="Admin adjustment" maxlength="255">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Apply Transaction
            </button>
        </form>
    </div>
</div>

<!-- Planets Tab -->
<div class="tab-content" id="planets-tab" style="display: none;">
    <div class="card">
        <div class="card-header">Planets ({{ $planets->count() }})</div>
        
        @if($planets->count() > 0)
            <div style="display: grid; gap: 1.25rem;">
                @foreach($planets as $planet)
                    <div style="background: var(--bg-tertiary); border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(59, 130, 246, 0.4)'" onmouseout="this.style.borderColor='var(--border)'">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <h3 style="font-weight: 700; font-size: 1.125rem; margin-bottom: 0.75rem;">{{ $planet->name }}</h3>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                                    <div>
                                        <div style="color: var(--text-muted); font-size: 0.8125rem;">Coordinates</div>
                                        <div style="font-family: monospace; color: var(--primary); font-weight: 600;">{{ $planet->galaxy }}:{{ $planet->system }}:{{ $planet->planet }}</div>
                                    </div>
                                    <div>
                                        <div style="color: var(--text-muted); font-size: 0.8125rem;">Type</div>
                                        <div style="font-weight: 500;">
                                            @if($planet->planet_type == 1)
                                                <span class="badge badge-primary">Planet</span>
                                            @else
                                                <span class="badge badge-secondary">Moon</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div style="color: var(--text-muted); font-size: 0.8125rem;">Fields</div>
                                        <div style="font-weight: 500;">{{ $planet->field_current }}/{{ $planet->field_max }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding: 2rem; text-align: center; color: var(--text-muted);">No planets</div>
        @endif
    </div>
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
        
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Permanently delete this user and all their planets. This action cannot be undone.</p>
        
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
            body: formData
        });
        
        if (response.ok) {
            Toast.show('User updated successfully', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error('Update failed');
        }
    } catch (error) {
        Toast.show('Failed to update user', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }
}

async function deleteUser(id, username) {
    if (!confirm(`Delete user "${username}"? This will also delete all their planets. This cannot be undone!`)) return;
    
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

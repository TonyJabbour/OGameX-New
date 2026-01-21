@extends('admin.layouts.admin')

@section('title', 'Image Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">Image Management</h1>
    <p class="page-description">Manage all game content images</p>
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

<!-- Upload Section -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Upload New Image</div>
    
    <form action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Select Image</label>
                <input type="file" name="image" class="form-input" accept="image/*" required>
                <div class="form-help">Max 5MB - JPG, PNG, GIF, WebP, SVG</div>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Category</label>
                <select name="category" class="form-input" required>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Custom Name (Optional)</label>
                <input type="text" name="custom_name" class="form-input" placeholder="Leave blank to keep original">
            </div>
            
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
    </form>
</div>

<!-- Category Filter -->
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.images.index', ['category' => 'all']) }}" 
           class="btn {{ $currentCategory === 'all' ? 'btn-primary' : 'btn-secondary' }}" 
           style="padding: 0.5rem 1rem;">
            All ({{ count($images) }})
        </a>
        @foreach($categories as $key => $label)
            <a href="{{ route('admin.images.index', ['category' => $key]) }}" 
               class="btn {{ $currentCategory === $key ? 'btn-primary' : 'btn-secondary' }}" 
               style="padding: 0.5rem 1rem;">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

<!-- Images Grid -->
<div class="card">
    <div class="card-header">
        Images 
        @if($currentCategory !== 'all')
            - {{ $categories[$currentCategory] }}
        @endif
        ({{ count($images) }})
    </div>
    
    @if(count($images) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; padding: 1rem;">
            @foreach($images as $image)
                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 0.75rem; overflow: hidden; transition: all 0.2s;">
                    <!-- Image Preview -->
                    <div style="aspect-ratio: 1; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img src="{{ $image['path'] }}" 
                             alt="{{ $image['name'] }}" 
                             style="max-width: 100%; max-height: 100%; object-fit: contain;"
                             loading="lazy">
                    </div>
                    
                    <!-- Image Info -->
                    <div style="padding: 1rem;">
                        <div style="font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; word-break: break-all;">
                            {{ $image['name'] }}
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <span style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ number_format($image['size'] / 1024, 1) }} KB
                            </span>
                            <span style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                {{ $image['category'] }}
                            </span>
                        </div>
                        
                        <!-- Actions -->
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="copyPath('{{ $image['path'] }}')" 
                                    class="btn btn-secondary" 
                                    style="flex: 1; padding: 0.5rem; font-size: 0.75rem;">
                                Copy Path
                            </button>
                            
                            <button onclick="renameImage('{{ $image['path'] }}', '{{ $image['name'] }}')" 
                                    class="btn btn-secondary" 
                                    style="padding: 0.5rem; font-size: 0.75rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            
                            <button onclick="deleteImage('{{ $image['path'] }}', '{{ $image['name'] }}')" 
                                    class="btn" 
                                    style="background: var(--danger); color: white; padding: 0.5rem; font-size: 0.75rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; opacity: 0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p>No images found in this category</p>
        </div>
    @endif
</div>

<!-- Hidden Forms for Actions -->
<form id="deleteForm" action="{{ route('admin.images.delete') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="path" id="deleteImagePath">
</form>

<form id="renameForm" action="{{ route('admin.images.rename') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="old_path" id="renameOldPath">
    <input type="hidden" name="new_name" id="renameNewName">
</form>

@push('scripts')
<script>
function copyPath(path) {
    navigator.clipboard.writeText(path).then(() => {
        alert('Path copied to clipboard: ' + path);
    });
}

function deleteImage(path, name) {
    if (confirm(`Delete image "${name}"? This cannot be undone.`)) {
        document.getElementById('deleteImagePath').value = path;
        document.getElementById('deleteForm').submit();
    }
}

function renameImage(path, currentName) {
    const newName = prompt('Enter new name (without extension):', currentName.replace(/\.[^/.]+$/, ''));
    if (newName && newName.trim()) {
        document.getElementById('renameOldPath').value = path;
        document.getElementById('renameNewName').value = newName.trim();
        document.getElementById('renameForm').submit();
    }
}

// Preview image on file select
document.querySelector('input[type="file"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        console.log('Selected file:', file.name, file.size, 'bytes');
    }
});
</script>
@endpush
@endsection

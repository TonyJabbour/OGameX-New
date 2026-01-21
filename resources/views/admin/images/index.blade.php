@extends('admin.layouts.admin')

@section('title', 'Image Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">Image Management</h1>
    <p class="page-description">Upload and organize game content images</p>
</div>

<!-- Upload Section with Drag & Drop -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">Upload Images</div>
    
    <form id="uploadForm" action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="dropzone" onclick="document.getElementById('imageInput').click()">
            <svg class="dropzone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <div class="dropzone-text">Drop images here or click to browse</div>
            <div class="dropzone-hint">Supports JPG, PNG, GIF, WebP, SVG (Max 5MB)</div>
            <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;" multiple>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-top: 1.5rem;">
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
                <input type="text" name="custom_name" class="form-input" placeholder="Auto-generated from filename">
            </div>
        </div>
        
        <div id="imagePreviewContainer" style="display: none; margin-top: 1.5rem;">
            <img id="imagePreview" style="max-width: 200px; max-height: 200px; border-radius: 0.75rem; border: 2px solid var(--border);">
        </div>
        
        <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem; width: 100%;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Upload Image
        </button>
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
        <div class="image-grid">
            @foreach($images as $image)
                <div class="image-card">
                    <div class="image-preview">
                        <img src="{{ $image['path'] }}" alt="{{ $image['name'] }}" loading="lazy">
                        <div class="image-actions">
                            <button onclick="copyPath('{{ $image['path'] }}')" class="btn btn-secondary" style="padding: 0.625rem;" title="Copy Path">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button onclick="renameImage('{{ $image['path'] }}', '{{ $image['name'] }}')" class="btn btn-secondary" style="padding: 0.625rem;" title="Rename">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteImage('{{ $image['path'] }}', '{{ $image['name'] }}')" class="btn btn-danger" style="padding: 0.625rem;" title="Delete">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="image-info">
                        <div class="image-name">{{ $image['name'] }}</div>
                        <div class="image-meta">
                            <span>{{ number_format($image['size'] / 1024, 1) }} KB</span>
                            <span class="badge badge-primary">{{ $image['category'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="padding: 4rem; text-align: center; color: var(--text-muted);">
            <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1.5rem; opacity: 0.3;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p style="font-size: 1.125rem; font-weight: 500;">No images found</p>
            <p style="font-size: 0.875rem; margin-top: 0.5rem;">Upload your first image to get started</p>
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
        Toast.show('Path copied to clipboard', 'success');
    }).catch(() => {
        Toast.show('Failed to copy path', 'error');
    });
}

async function deleteImage(path, name) {
    if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
    
    try {
        const formData = new FormData();
        formData.append('path', path);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch('{{ route('admin.images.delete') }}', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            Toast.show('Image deleted successfully', 'success');
            // Remove image card from DOM
            event.target.closest('.image-card').remove();
            // Update count
            const header = document.querySelector('.card-header');
            if (header) {
                const countMatch = header.textContent.match(/\((\d+)\)/);
                if (countMatch) {
                    const newCount = parseInt(countMatch[1]) - 1;
                    header.textContent = header.textContent.replace(/\(\d+\)/, `(${newCount})`);
                }
            }
        } else {
            throw new Error('Delete failed');
        }
    } catch (error) {
        Toast.show('Failed to delete image', 'error');
    }
}

async function renameImage(path, currentName) {
    const newName = prompt('Enter new name (without extension):', currentName.replace(/\.[^/.]+$/, ''));
    if (!newName || !newName.trim()) return;
    
    try {
        const formData = new FormData();
        formData.append('old_path', path);
        formData.append('new_name', newName.trim());
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch('{{ route('admin.images.rename') }}', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            Toast.show('Image renamed successfully', 'success');
            // Update image name in DOM
            const imageCard = Array.from(document.querySelectorAll('.image-card')).find(card => {
                return card.querySelector('img')?.src.includes(path);
            });
            if (imageCard) {
                const nameElement = imageCard.querySelector('.image-name');
                if (nameElement) {
                    const extension = currentName.split('.').pop();
                    nameElement.textContent = newName.trim() + '.' + extension;
                }
            }
        } else {
            throw new Error('Rename failed');
        }
    } catch (error) {
        Toast.show('Failed to rename image', 'error');
    }
}

// Image preview on file select
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');
            if (preview && container) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
});

// Upload form with AJAX
document.getElementById('uploadForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Uploading...';
    
    try {
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            Toast.show('Image uploaded successfully. Refresh to see new image.', 'success');
            // Reset form
            this.reset();
            document.getElementById('imagePreviewContainer').style.display = 'none';
        } else {
            throw new Error('Upload failed');
        }
    } catch (error) {
        Toast.show('Failed to upload image', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }
});
</script>
@endpush
@endsection

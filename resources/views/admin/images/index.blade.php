@extends('admin.layouts.admin')

@section('title', 'Image Management')

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title">Image Management</h1>
            <p class="page-description">Upload and organize game content images</p>
        </div>
        <button onclick="Modal.open('uploadModal')" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Upload Images
        </button>
    </div>
</div>

<!-- Category Filter -->
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
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
            <a href="{{ route('admin.images.index', ['category' => 'unused']) }}" 
               class="btn {{ $currentCategory === 'unused' ? 'btn-primary' : 'btn-secondary' }}" 
               style="padding: 0.5rem 1rem; background: {{ $currentCategory === 'unused' ? '' : 'rgba(100, 116, 139, 0.2)' }}; border-color: rgba(100, 116, 139, 0.4);">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                    <circle cx="12" cy="12" r="10"/>
                </svg>
                Unused ({{ $unusedCount }})
            </a>
        </div>
        
        @if($unusedCount > 0)
            <button onclick="archiveUnused()" class="btn btn-warning">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Archive All Unused ({{ $unusedCount }})
            </button>
        @endif
    </div>
</div>

<!-- Images Grid -->
<div class="card">
    <div class="card-header">
        Images 
        @if($currentCategory !== 'all')
            @if($currentCategory === 'unused')
                - Unused
            @else
                - {{ $categories[$currentCategory] }}
            @endif
        @endif
        ({{ count($images) }})
    </div>
    
    @if(count($images) > 0)
        <div class="image-grid">
            @foreach($images as $image)
                <div class="image-card" style="position: relative;">
                    <!-- Usage Status Badge -->
                    <div style="position: absolute; top: 0.75rem; right: 0.75rem; z-index: 10;">
                        @if($image['is_used'])
                            <span class="badge badge-success" style="display: flex; align-items: center; gap: 0.375rem; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                                Used {{ $image['usage_count'] }}x
                            </span>
                        @else
                            <span class="badge" style="background: rgba(100, 116, 139, 0.2); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.4); display: flex; align-items: center; gap: 0.375rem;">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                                Unused
                            </span>
                        @endif
                    </div>
                    
                    <div class="image-preview">
                        <img src="{{ $image['path'] }}" alt="{{ $image['name'] }}" loading="lazy">
                        <div class="image-actions">
                            <button onclick="copyPath('{{ $image['path'] }}')" class="btn btn-secondary" style="padding: 0.625rem;" title="Copy Path">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button onclick="openReplaceModal('{{ $image['path'] }}', '{{ $image['name'] }}')" class="btn btn-secondary" style="padding: 0.625rem;" title="Replace Image">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
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

<!-- Upload Modal -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Upload Images</h3>
            <button class="modal-close" onclick="Modal.close('uploadModal')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="uploadForm" action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="dropzone" onclick="document.getElementById('imageInput').click()">
                    <svg class="dropzone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <div class="dropzone-text">Drop images here or click to browse</div>
                    <div class="dropzone-hint">Supports JPG, PNG, GIF, WebP, SVG (Max 5MB)</div>
                    <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
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
                
                <div id="imagePreviewContainer" style="display: none; margin-top: 1.5rem; text-align: center;">
                    <img id="imagePreview" style="max-width: 100%; max-height: 300px; border-radius: 0.75rem; border: 2px solid var(--border);">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button onclick="Modal.close('uploadModal')" class="btn btn-secondary">Cancel</button>
            <button onclick="document.getElementById('uploadForm').dispatchEvent(new Event('submit', {bubbles: true, cancelable: true}))" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Upload Image
            </button>
        </div>
    </div>
</div>

<!-- Replace Image Modal -->
<div class="modal-overlay" id="replaceModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Replace Image</h3>
            <button class="modal-close" onclick="Modal.close('replaceModal')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 0.75rem;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; color: var(--warning);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <div style="font-weight: 600; color: var(--warning); margin-bottom: 0.25rem;">Important</div>
                        <div style="color: var(--text-secondary); font-size: 0.875rem;">
                            The new image will replace the existing file with the same filename. This ensures all code references remain valid.
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">Current Image:</div>
                <div style="font-weight: 600; font-family: monospace; color: var(--primary);" id="replaceImageName"></div>
            </div>
            
            <form id="replaceForm" action="{{ route('admin.images.replace') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="old_path" id="replaceOldPath">
                
                <div class="dropzone" onclick="document.getElementById('replaceInput').click()" style="margin-bottom: 1.5rem;">
                    <svg class="dropzone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <div class="dropzone-text">Drop new image here or click to browse</div>
                    <div class="dropzone-hint">Must be same format as original</div>
                    <input type="file" id="replaceInput" name="image" accept="image/*" style="display: none;">
                </div>
                
                <div id="replacePreviewContainer" style="display: none; text-align: center;">
                    <img id="replacePreview" style="max-width: 100%; max-height: 300px; border-radius: 0.75rem; border: 2px solid var(--border);">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button onclick="Modal.close('replaceModal')" class="btn btn-secondary">Cancel</button>
            <button onclick="submitReplace()" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Replace Image
            </button>
        </div>
    </div>
</div>

<!-- Hidden Form for Delete -->
<form id="deleteForm" action="{{ route('admin.images.delete') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="path" id="deleteImagePath">
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

function openReplaceModal(path, name) {
    document.getElementById('replaceOldPath').value = path;
    document.getElementById('replaceImageName').textContent = name;
    document.getElementById('replacePreviewContainer').style.display = 'none';
    document.getElementById('replaceInput').value = '';
    Modal.open('replaceModal');
}

async function submitReplace() {
    const form = document.getElementById('replaceForm');
    const fileInput = document.getElementById('replaceInput');
    
    if (!fileInput.files || fileInput.files.length === 0) {
        Toast.show('Please select an image to upload', 'warning');
        return;
    }
    
    const modalBtn = document.querySelector('#replaceModal .modal-footer .btn-primary');
    const originalHTML = modalBtn.innerHTML;
    
    modalBtn.disabled = true;
    modalBtn.innerHTML = '<span class="spinner"></span> Replacing...';
    
    try {
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            Toast.show('Image replaced successfully', 'success');
            Modal.close('replaceModal');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Replace failed');
        }
    } catch (error) {
        Toast.show('Failed to replace image', 'error');
    } finally {
        modalBtn.disabled = false;
        modalBtn.innerHTML = originalHTML;
    }
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

// Preview for replace modal
document.getElementById('replaceInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('replacePreview');
            const container = document.getElementById('replacePreviewContainer');
            if (preview && container) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }
});

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

async function archiveUnused() {
    if (!confirm(`Archive all {{ $unusedCount }} unused images? They will be moved to /img/archive/ organized by category.`)) return;
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch('{{ route('admin.images.archive-unused') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            Toast.show('Unused images archived successfully', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error('Archive failed');
        }
    } catch (error) {
        Toast.show('Failed to archive images', 'error');
    }
}

// Upload form with AJAX
document.getElementById('uploadForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const modalFooterBtn = document.querySelector('#uploadModal .modal-footer .btn-primary');
    const originalHTML = modalFooterBtn.innerHTML;
    
    modalFooterBtn.disabled = true;
    modalFooterBtn.innerHTML = '<span class="spinner"></span> Uploading...';
    
    try {
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            Toast.show('Image uploaded successfully', 'success');
            // Reset form and close modal
            this.reset();
            document.getElementById('imagePreviewContainer').style.display = 'none';
            Modal.close('uploadModal');
            // Reload page to show new image
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Upload failed');
        }
    } catch (error) {
        Toast.show('Failed to upload image', 'error');
    } finally {
        modalFooterBtn.disabled = false;
        modalFooterBtn.innerHTML = originalHTML;
    }
});
</script>
@endpush
@endsection

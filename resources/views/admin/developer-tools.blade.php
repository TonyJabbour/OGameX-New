@extends('admin.layouts.admin')

@section('title', 'Developer Tools')

@section('content')
<div class="page-header">
    <h1 class="page-title">Developer Tools</h1>
    <p class="page-description">Quick actions and testing utilities</p>
</div>

@if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<!-- Tabs -->
<div class="tabs">
    <a href="#quick" class="tab active" data-tab="quick">Quick Actions</a>
    <a href="#resources" class="tab" data-tab="resources">Resources</a>
    <a href="#universe" class="tab" data-tab="universe">Universe</a>
    <a href="#testing" class="tab" data-tab="testing">Testing</a>
</div>

<!-- Quick Actions Tab -->
<div class="tab-content" id="quick-tab">
    <div class="card">
        <div class="card-header">Current Planet Tools</div>
        <p style="color: var(--text-secondary); margin-bottom: 1rem; font-size: 0.875rem;">Current Planet: <strong>{{ $currentPlanet->getPlanetName() }}</strong> ({{ $currentPlanet->getPlanetCoordinates()->asString() }})</p>
        
        <form action="{{ route('admin.developershortcuts.update-resources') }}" method="POST">
            @csrf
            <input type="hidden" name="galaxy" value="{{ $currentPlanet->getPlanetCoordinates()->galaxy }}">
            <input type="hidden" name="system" value="{{ $currentPlanet->getPlanetCoordinates()->system }}">
            <input type="hidden" name="position" value="{{ $currentPlanet->getPlanetCoordinates()->position }}">
            <input type="hidden" name="update_resources_planet" value="1">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Metal</label>
                    <input type="number" name="metal" class="form-input" value="1000000" step="1000">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Crystal</label>
                    <input type="number" name="crystal" class="form-input" value="1000000" step="1000">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deuterium</label>
                    <input type="number" name="deuterium" class="form-input" value="500000" step="1000">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Resources to Current Planet</button>
        </form>
    </div>
    
    <div class="card">
        <div class="card-header">Set Building Level</div>
        
        <form action="{{ route('admin.developershortcuts.update') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="set_building">
            
            <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Building</label>
                    <select name="building" class="form-input">
                        <option value="metal_mine">Metal Mine</option>
                        <option value="crystal_mine">Crystal Mine</option>
                        <option value="deuterium_synthesizer">Deuterium Synthesizer</option>
                        <option value="solar_plant">Solar Plant</option>
                        <option value="fusion_plant">Fusion Plant</option>
                        <option value="robot_factory">Robot Factory</option>
                        <option value="nano_factory">Nanite Factory</option>
                        <option value="shipyard">Shipyard</option>
                        <option value="research_lab">Research Lab</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Level</label>
                    <input type="number" name="level" class="form-input" value="10" min="0" max="100">
                </div>
                
                <button type="submit" class="btn btn-primary">Set Level</button>
            </div>
        </form>
    </div>
    
    <div class="card">
        <div class="card-header">Set Research Level</div>
        
        <form action="{{ route('admin.developershortcuts.update') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="set_research">
            
            <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end;">
                <div class="form-group">
                    <label class="form-label">Technology</label>
                    <select name="research" class="form-input">
                        <option value="energy_technology">Energy Technology</option>
                        <option value="laser_technology">Laser Technology</option>
                        <option value="ion_technology">Ion Technology</option>
                        <option value="hyperspace_technology">Hyperspace Technology</option>
                        <option value="plasma_technology">Plasma Technology</option>
                        <option value="combustion_drive">Combustion Drive</option>
                        <option value="impulse_drive">Impulse Drive</option>
                        <option value="hyperspace_drive">Hyperspace Drive</option>
                        <option value="espionage_technology">Espionage Technology</option>
                        <option value="computer_technology">Computer Technology</option>
                        <option value="astrophysics">Astrophysics</option>
                        <option value="intergalactic_research_network">Intergalactic Research Network</option>
                        <option value="weapons_technology">Weapons Technology</option>
                        <option value="shielding_technology">Shielding Technology</option>
                        <option value="armour_technology">Armour Technology</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Level</label>
                    <input type="number" name="level" class="form-input" value="10" min="0" max="100">
                </div>
                
                <button type="submit" class="btn btn-primary">Set Level</button>
            </div>
        </form>
    </div>
</div>

<!-- Resources Tab -->
<div class="tab-content" id="resources-tab" style="display: none;">
    <div class="card">
        <div class="card-header">Add/Subtract Resources at Coordinates</div>
        
        <form action="{{ route('admin.developershortcuts.update-resources') }}" method="POST">
            @csrf
            <input type="hidden" name="use_coordinates" value="1">
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Galaxy</label>
                    <input type="number" name="galaxy" class="form-input" value="1" min="1" max="10">
                </div>
                
                <div class="form-group">
                    <label class="form-label">System</label>
                    <input type="number" name="system" class="form-input" value="1" min="1" max="499">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="number" name="position" class="form-input" value="1" min="1" max="15">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Metal</label>
                    <input type="number" name="metal" class="form-input" value="0" step="1000">
                    <div class="form-help">Use negative values to subtract</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Crystal</label>
                    <input type="number" name="crystal" class="form-input" value="0" step="1000">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deuterium</label>
                    <input type="number" name="deuterium" class="form-input" value="0" step="1000">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Apply Resource Changes</button>
        </form>
    </div>
</div>

<!-- Universe Tab -->
<div class="tab-content" id="universe-tab" style="display: none;">
    <div class="card">
        <div class="card-header">Create Planet at Coordinates</div>
        
        <form action="{{ route('admin.developershortcuts.create-at-coords') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="planet">
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Galaxy</label>
                    <input type="number" name="galaxy" class="form-input" value="1" min="1" max="10" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">System</label>
                    <input type="number" name="system" class="form-input" value="1" min="1" max="499" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="number" name="position" class="form-input" value="1" min="1" max="15" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Planet Name</label>
                <input type="text" name="planet_name" class="form-input" value="Test Planet" maxlength="20">
            </div>
            
            <button type="submit" class="btn btn-primary">Create Planet</button>
        </form>
    </div>
    
    <div class="card">
        <div class="card-header">Create Debris Field</div>
        
        <form action="{{ route('admin.developershortcuts.create-debris') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Galaxy</label>
                    <input type="number" name="galaxy" class="form-input" value="1" min="1" max="10" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">System</label>
                    <input type="number" name="system" class="form-input" value="1" min="1" max="499" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Position</label>
                    <input type="number" name="position" class="form-input" value="1" min="1" max="15" required>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Metal Debris</label>
                    <input type="number" name="metal" class="form-input" value="100000" step="10000">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Crystal Debris</label>
                    <input type="number" name="crystal" class="form-input" value="100000" step="10000">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Debris Field</button>
        </form>
    </div>
</div>

<!-- Testing Tab -->
<div class="tab-content" id="testing-tab" style="display: none;">
    <div class="card">
        <div class="card-header">Character Class Selection</div>
        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Redirect to character class selection page for testing</p>
        
        <a href="{{ route('characterclass.index') }}" class="btn btn-primary">Open Character Class Selection</a>
    </div>
    
    <div class="card">
        <div class="card-header">Cache Management</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <button type="button" class="btn btn-secondary" onclick="clearCache('view')">Clear View Cache</button>
            <button type="button" class="btn btn-secondary" onclick="clearCache('route')">Clear Route Cache</button>
            <button type="button" class="btn btn-secondary" onclick="clearCache('config')">Clear Config Cache</button>
            <button type="button" class="btn btn-secondary" onclick="clearCache('all')">Clear All Caches</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.style.display = 'none');
            
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab') + '-tab';
            document.getElementById(tabId).style.display = 'block';
        });
    });
});

function clearCache(type) {
    if (confirm(`Clear ${type} cache?`)) {
        fetch('/admin/developer-tools/clear-cache', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Cache cleared successfully');
        })
        .catch(error => {
            alert('Error clearing cache');
        });
    }
}
</script>
@endpush
@endsection

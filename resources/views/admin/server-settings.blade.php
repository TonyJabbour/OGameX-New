@extends('admin.layouts.admin')

@section('title', 'Server Settings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Server Settings</h1>
    <p class="page-description">Configure your game server parameters</p>
</div>

@if (session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<!-- Tabs -->
<div class="tabs">
    <a href="#universe" class="tab active" data-tab="universe">Universe</a>
    <a href="#economy" class="tab" data-tab="economy">Economy</a>
    <a href="#battle" class="tab" data-tab="battle">Battle</a>
    <a href="#players" class="tab" data-tab="players">Players</a>
    <a href="#advanced" class="tab" data-tab="advanced">Advanced</a>
</div>

<form id="settingsForm" action="{{ route('admin.serversettings.update') }}" method="POST" onsubmit="saveSettings(event)">
    @csrf
    
    <!-- Universe Tab -->
    <div class="tab-content" id="universe-tab">
        <div class="card">
            <div class="card-header">Basic Universe Settings</div>
            
            <div class="form-group">
                <label class="form-label">Universe Name</label>
                <input type="text" name="universe_name" class="form-input" value="{{ $universe_name }}" maxlength="20">
                <div class="form-help">The name of your game universe displayed to players</div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Number of Galaxies</label>
                <input type="number" name="number_of_galaxies" class="form-input" value="{{ $number_of_galaxies }}" min="1" max="10">
                <div class="form-help">Total galaxies in the universe (1-10)</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Galaxy Display</div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="ignore_empty_systems_on" value="1" {{ $ignore_empty_systems_on ? 'checked' : '' }} style="margin-right: 0.5rem;">
                    <span class="form-label" style="margin: 0;">Hide Empty Systems</span>
                </label>
                <div class="form-help">Don't show systems with no planets in galaxy view</div>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="ignore_inactive_systems_on" value="1" {{ $ignore_inactive_systems_on ? 'checked' : '' }} style="margin-right: 0.5rem;">
                    <span class="form-label" style="margin: 0;">Hide Inactive Systems</span>
                </label>
                <div class="form-help">Don't show systems with only inactive players</div>
            </div>
        </div>
    </div>
    
    <!-- Economy Tab -->
    <div class="tab-content" id="economy-tab" style="display: none;">
        <div class="card">
            <div class="card-header">Production Speed</div>
            
            <div class="form-group">
                <label class="form-label">Economy Speed Multiplier</label>
                <input type="number" name="economy_speed" class="form-input" value="{{ $economy_speed }}" min="1" max="100" step="1">
                <div class="form-help">Affects metal, crystal, deuterium production and building construction</div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Research Speed Multiplier</label>
                <input type="number" name="research_speed" class="form-input" value="{{ $research_speed }}" min="1" max="100" step="1">
                <div class="form-help">Affects research time</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Base Income (Per Hour)</div>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem;">These values are applied BEFORE the economy speed multiplier</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Base Metal Income</label>
                    <input type="number" name="basic_income_metal" class="form-input" value="{{ $basic_income_metal }}" min="0" max="1000">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Base Crystal Income</label>
                    <input type="number" name="basic_income_crystal" class="form-input" value="{{ $basic_income_crystal }}" min="0" max="500">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Base Deuterium Income</label>
                    <input type="number" name="basic_income_deuterium" class="form-input" value="{{ $basic_income_deuterium }}" min="0" max="100">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Base Energy Income</label>
                    <input type="number" name="basic_income_energy" class="form-input" value="{{ $basic_income_energy }}" min="0" max="500">
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Planet Fields</div>
            
            <div class="form-group">
                <label class="form-label">Planet Field Bonus</label>
                <input type="number" name="planet_fields_bonus" class="form-input" value="{{ $planet_fields_bonus }}" min="0" max="50">
                <div class="form-help">Extra building fields added to all planets</div>
            </div>
        </div>
    </div>
    
    <!-- Battle Tab -->
    <div class="tab-content" id="battle-tab" style="display: none;">
        <div class="card">
            <div class="card-header">Battle Engine</div>
            
            <div class="form-group">
                <label class="form-label">Battle Engine</label>
                <select name="battle_engine" class="form-input">
                    <option value="rust" {{ $battle_engine == 'rust' ? 'selected' : '' }}>Rust (Recommended)</option>
                    <option value="php" {{ $battle_engine == 'php' ? 'selected' : '' }}>PHP (Legacy)</option>
                </select>
                <div class="form-help">Rust engine is faster and more accurate</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Alliance Combat System (ACS)</div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="alliance_combat_system_on" value="1" {{ $alliance_combat_system_on ? 'checked' : '' }} style="margin-right: 0.5rem;">
                    <span class="form-label" style="margin: 0;">Enable Alliance Combat System</span>
                </label>
                <div class="form-help">Allow multiple players to attack/defend together</div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Alliance Cooldown (Days)</label>
                <input type="number" name="alliance_cooldown_days" class="form-input" value="{{ $alliance_cooldown_days }}" min="0" max="30">
                <div class="form-help">Days to wait after leaving before joining/creating another alliance</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Debris Fields</div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Ships → Debris (%)</label>
                    <input type="number" name="debris_field_from_ships" class="form-input" value="{{ $debris_field_from_ships }}" min="0" max="80">
                    <div class="form-help">Percentage of destroyed ships that become debris</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Defense → Debris (%)</label>
                    <input type="number" name="debris_field_from_defense" class="form-input" value="{{ $debris_field_from_defense }}" min="0" max="80">
                    <div class="form-help">Percentage of destroyed defenses that become debris</div>
                </div>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="debris_field_deuterium_on" value="1" {{ $debris_field_deuterium_on ? 'checked' : '' }} style="margin-right: 0.5rem;">
                    <span class="form-label" style="margin: 0;">Include Deuterium in Debris</span>
                </label>
                <div class="form-help">Destroyed ships also create deuterium debris</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Wreck Fields</div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Min Resources Loss</label>
                    <input type="number" name="wreck_field_min_resources_loss" class="form-input" value="{{ $wreck_field_min_resources_loss }}" min="1000" max="10000000" step="1000">
                    <div class="form-help">Minimum destruction value for wreck field</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Min Fleet Destruction (%)</label>
                    <input type="number" name="wreck_field_min_fleet_percentage" class="form-input" value="{{ $wreck_field_min_fleet_percentage }}" min="0" max="100">
                    <div class="form-help">Minimum fleet percentage destroyed</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Wreck Lifetime (Hours)</label>
                    <input type="number" name="wreck_field_lifetime_hours" class="form-input" value="{{ $wreck_field_lifetime_hours }}" min="1" max="168">
                    <div class="form-help">How long wreck fields remain</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Max Repair Time (Hours)</label>
                    <input type="number" name="wreck_field_repair_max_hours" class="form-input" value="{{ $wreck_field_repair_max_hours }}" min="1" max="72">
                    <div class="form-help">Maximum time to repair wrecks</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Min Repair Time (Minutes)</label>
                    <input type="number" name="wreck_field_repair_min_minutes" class="form-input" value="{{ $wreck_field_repair_min_minutes }}" min="1" max="60">
                    <div class="form-help">Minimum time to repair wrecks</div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Moon Formation</div>
            
            <div class="form-group">
                <label class="form-label">Maximum Moon Chance (%)</label>
                <input type="number" name="maximum_moon_chance" class="form-input" value="{{ $maximum_moon_chance }}" min="0" max="100">
                <div class="form-help">Maximum probability of moon formation after battle</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Special Mechanics</div>
            
            <div class="form-group">
                <label class="form-label">Hamill Manoeuvre Probability (1 in X)</label>
                <input type="number" name="hamill_probability" class="form-input" value="{{ $hamill_probability }}" min="10" max="10000">
                <div class="form-help">Chance for Light Fighter to destroy Death Star (1000 = 0.1%, 10 = 10%)</div>
            </div>
        </div>
    </div>
    
    <!-- Players Tab -->
    <div class="tab-content" id="players-tab" style="display: none;">
        <div class="card">
            <div class="card-header">New Player Defaults</div>
            
            <div class="form-group">
                <label class="form-label">Starting Planets</label>
                <input type="number" name="registration_planet_amount" class="form-input" value="{{ $registration_planet_amount }}" min="1" max="3">
                <div class="form-help">Number of planets new players start with</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Dark Matter System</div>
            
            <div class="form-group">
                <label class="form-label">Starting Dark Matter</label>
                <input type="number" name="dark_matter_bonus" class="form-input" value="{{ $dark_matter_bonus }}" min="0" max="100000">
                <div class="form-help">Dark matter given to new players on registration</div>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="dark_matter_regen_enabled" value="1" {{ $dark_matter_regen_enabled ? 'checked' : '' }} style="margin-right: 0.5rem;">
                    <span class="form-label" style="margin: 0;">Enable Dark Matter Regeneration</span>
                </label>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Regen Amount</label>
                    <input type="number" name="dark_matter_regen_amount" class="form-input" value="{{ $dark_matter_regen_amount }}" min="0" max="1000">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Regen Period (Seconds)</label>
                    <input type="number" name="dark_matter_regen_period" class="form-input" value="{{ $dark_matter_regen_period }}" min="60" max="86400">
                    <div class="form-help">86400 = 1 day</div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Highscore System</div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="highscore_admin_visible" value="1" {{ $highscore_admin_visible ? 'checked' : '' }} style="margin-right: 0.5rem;">
                    <span class="form-label" style="margin: 0;">Show Admins in Highscore</span>
                </label>
                <div class="form-help">When enabled, admins appear with orange highlight. When disabled, admins are excluded from rankings.</div>
            </div>
        </div>
    </div>
    
    <!-- Advanced Tab -->
    <div class="tab-content" id="advanced-tab" style="display: none;">
        <div class="card">
            <div class="card-header">Fleet Speeds</div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">War Fleet Speed</label>
                    <input type="number" name="fleet_speed_war" class="form-input" value="{{ $fleet_speed_war }}" min="1" max="100">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Holding Fleet Speed</label>
                    <input type="number" name="fleet_speed_holding" class="form-input" value="{{ $fleet_speed_holding }}" min="1" max="100">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Peaceful Fleet Speed</label>
                    <input type="number" name="fleet_speed_peaceful" class="form-input" value="{{ $fleet_speed_peaceful }}" min="1" max="100">
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Expedition System</div>
            
            <div class="form-group">
                <label class="form-label">Bonus Expedition Slots</label>
                <input type="number" name="bonus_expedition_slots" class="form-input" value="{{ $bonus_expedition_slots }}" min="0" max="10">
                <div class="form-help">Extra expedition slots for all players</div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Resource Multiplier</label>
                    <input type="number" name="expedition_reward_multiplier_resources" class="form-input" value="{{ $expedition_reward_multiplier_resources }}" min="0.1" max="10" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ship Multiplier</label>
                    <input type="number" name="expedition_reward_multiplier_ships" class="form-input" value="{{ $expedition_reward_multiplier_ships }}" min="0.1" max="10" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Dark Matter Multiplier</label>
                    <input type="number" name="expedition_reward_multiplier_dark_matter" class="form-input" value="{{ $expedition_reward_multiplier_dark_matter }}" min="0.1" max="10" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Item Multiplier</label>
                    <input type="number" name="expedition_reward_multiplier_items" class="form-input" value="{{ $expedition_reward_multiplier_items }}" min="0.1" max="10" step="0.1">
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Expedition Outcome Weights</div>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem;">Higher values = more frequent. Set to 0 to disable outcome.</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Resources</label>
                    <input type="number" name="expedition_weight_resources" class="form-input" value="{{ $expedition_weight_resources }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Ships</label>
                    <input type="number" name="expedition_weight_ships" class="form-input" value="{{ $expedition_weight_ships }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Dark Matter</label>
                    <input type="number" name="expedition_weight_dark_matter" class="form-input" value="{{ $expedition_weight_dark_matter }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nothing/Failed</label>
                    <input type="number" name="expedition_weight_nothing" class="form-input" value="{{ $expedition_weight_nothing }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Delay</label>
                    <input type="number" name="expedition_weight_delay" class="form-input" value="{{ $expedition_weight_delay }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Speedup</label>
                    <input type="number" name="expedition_weight_speedup" class="form-input" value="{{ $expedition_weight_speedup }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Pirates</label>
                    <input type="number" name="expedition_weight_pirates" class="form-input" value="{{ $expedition_weight_pirates }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Aliens</label>
                    <input type="number" name="expedition_weight_aliens" class="form-input" value="{{ $expedition_weight_aliens }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Merchant</label>
                    <input type="number" name="expedition_weight_merchant" class="form-input" value="{{ $expedition_weight_merchant }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Items</label>
                    <input type="number" name="expedition_weight_items" class="form-input" value="{{ $expedition_weight_items }}" min="0" max="100" step="0.1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Black Hole</label>
                    <input type="number" name="expedition_weight_black_hole" class="form-input" value="{{ $expedition_weight_black_hole }}" min="0" max="100" step="0.1">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div style="position: sticky; bottom: 0; background: var(--bg-primary); padding: 1.5rem 0; border-top: 1px solid var(--border); margin-top: 2rem;">
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem;">
            Save Changes
        </button>
    </div>
</form>

@push('scripts')
<script>
async function saveSettings(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Saving...';
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            Toast.show('Settings saved successfully', 'success');
            // Don't reload - just show success
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to save settings');
        }
    } catch (error) {
        Toast.show(error.message || 'Failed to save settings', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHTML;
    }
}
</script>
@endpush
@endsection

# OGameX Admin Panel - Implementation Guide

## Project Overview
Complete redesign of admin functionality into a modern, dedicated admin panel at `/admin` with comprehensive settings management and developer tools.

## Implementation Status

### ✅ Phase 1: Database Foundation (COMPLETED)
- [x] Created `server_settings` table migration
- [x] Created `server_settings_history` table migration  
- [x] Created `ServerSetting` model with type casting and caching

### 🔄 Phase 2: Core Infrastructure (IN PROGRESS)
- [ ] Enhanced SettingsService to use database
- [ ] Admin authentication middleware
- [ ] Base admin layout with sidebar
- [ ] Admin dashboard controller
- [ ] Settings seeder for migration

### 📋 Phase 3: Server Settings Pages
- [ ] Universe Configuration tab
- [ ] Economy & Resources tab
- [ ] Battle & Combat tab
- [ ] Players & New Accounts tab
- [ ] Advanced Settings tab

### 📋 Phase 4: Developer Tools Pages
- [ ] Quick Actions tab
- [ ] Resources & Economy tab
- [ ] Universe Management tab
- [ ] Testing & Debug tab

### 📋 Phase 5: Polish & Features
- [ ] Validation system
- [ ] Change tracking
- [ ] Search functionality
- [ ] Responsive design
- [ ] Testing & QA

## Next Steps

1. **Enhance SettingsService** - Update to read/write from database instead of cache-only
2. **Create Admin Middleware** - Ensure only admins can access /admin routes
3. **Build Base Layout** - Create modern admin panel layout with sidebar navigation
4. **Implement First Tab** - Start with Universe Configuration as proof of concept
5. **Iterate** - Build remaining tabs systematically

## Database Schema

### server_settings
```sql
- id (bigint, primary key)
- key (string, unique) - e.g., 'universe_name', 'economy_speed'
- value (text) - stored as string, cast by type
- type (string) - 'string', 'integer', 'float', 'boolean', 'json'
- category (string) - 'universe', 'economy', 'battle', 'players', 'advanced'
- section (string) - sub-category for organization
- description (text) - help text for admins
- default_value (string) - fallback if not set
- validation_rules (string) - JSON encoded validation
- updated_by (bigint, foreign key to users)
- timestamps
```

### server_settings_history
```sql
- id (bigint, primary key)
- setting_key (string, indexed)
- old_value (text)
- new_value (text)
- changed_by (bigint, foreign key to users)
- change_reason (string)
- ip_address (string)
- changed_at (timestamp, indexed)
```

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ServerSettingsController.php (enhanced)
│   │       └── DeveloperToolsController.php
│   └── Middleware/
│       └── AdminAccess.php
├── Models/
│   ├── ServerSetting.php ✅
│   └── ServerSettingHistory.php
└── Services/
    └── SettingsService.php (enhanced)

database/
├── migrations/
│   ├── 2026_01_21_000001_create_server_settings_table.php ✅
│   ├── 2026_01_21_000002_create_server_settings_history_table.php ✅
│   └── 2026_01_21_000003_seed_server_settings.php
└── seeders/
    └── ServerSettingsSeeder.php

resources/
└── views/
    └── admin/
        ├── layouts/
        │   └── admin.blade.php
        ├── dashboard.blade.php
        ├── server-settings/
        │   ├── index.blade.php
        │   ├── universe.blade.php
        │   ├── economy.blade.php
        │   ├── battle.blade.php
        │   ├── players.blade.php
        │   └── advanced.blade.php
        └── developer-tools/
            ├── index.blade.php
            ├── quick-actions.blade.php
            ├── resources.blade.php
            ├── universe.blade.php
            └── testing.blade.php

public/
├── css/
│   └── admin.css
└── js/
    └── admin.js

routes/
└── web.php (add /admin routes)
```

## Settings Categories

### Universe (25 settings)
- universe_name, number_of_galaxies, systems_per_galaxy
- ignore_empty_systems_on, ignore_inactive_systems_on
- server_timezone, game_tick_rate, etc.

### Economy (30 settings)
- economy_speed, research_speed, building_speed, shipyard_speed
- basic_income_metal/crystal/deuterium/energy
- storage_upgrade_factor, planet_fields_bonus, etc.

### Battle (40 settings)
- battle_engine, acs_enabled, debris_field_from_ships/defense
- wreck_field settings, moon_formation settings
- hamill_manoeuvre_chance, rapid_fire_enabled, etc.

### Players (25 settings)
- starting_planets/metal/crystal/deuterium
- dark_matter settings, alliance_cooldown_days
- character_class settings, highscore settings, etc.

### Advanced (35 settings)
- fleet_speed_war/holding/peaceful/expedition
- expedition settings and outcome weights
- security_limits, max_planets_per_player, etc.

**Total: ~155 settings** (currently ~50 in code)

## UI Components Needed

### Reusable Components
- SettingInput (handles all input types)
- SettingSlider (for multipliers and percentages)
- SettingToggle (for boolean values)
- CoordinateInput (G:__ S:__ P:__)
- ResourceInput (with k/m/b support)
- ValidationMessage
- HelpTooltip
- ChangeIndicator
- SaveButton (with loading state)

### Layout Components
- AdminSidebar
- AdminHeader
- AdminBreadcrumb
- TabNavigation
- SettingSection (card wrapper)
- ConfirmDialog
- SuccessToast

## Migration Strategy

1. **Create seeder** with all current settings from SettingsService
2. **Run migration** to create tables
3. **Run seeder** to populate initial values
4. **Update SettingsService** to read from database (keep cache)
5. **Test** that game still works with database settings
6. **Deploy admin panel** for setting management
7. **Remove hardcoded defaults** after 1 week of testing

## Testing Checklist

- [ ] All current settings migrated correctly
- [ ] Settings save to database
- [ ] Settings load from database
- [ ] Cache invalidation works
- [ ] Change history tracks modifications
- [ ] Only admins can access /admin
- [ ] Non-admins redirected properly
- [ ] All validation rules work
- [ ] Responsive design on mobile/tablet
- [ ] No breaking changes to game functionality

## Performance Considerations

- Settings cached for 5 minutes
- Bulk operations use transactions
- History table indexed properly
- Lazy loading for setting lists
- Pagination for history view
- Debounced auto-save (optional)

## Security Measures

- Admin middleware on all /admin routes
- CSRF protection on all forms
- Confirmation dialogs for destructive actions
- IP logging for all changes
- Rate limiting on save operations
- Input sanitization and validation
- SQL injection prevention (Eloquent ORM)

## Notes for Future Sessions

This is a large project that will require multiple sessions. Priority order:

1. **Session 1** (Current): Database foundation ✅
2. **Session 2**: Enhanced SettingsService + Admin middleware
3. **Session 3**: Base admin layout + Dashboard
4. **Session 4**: Universe Configuration tab (proof of concept)
5. **Session 5**: Economy & Resources tab
6. **Session 6**: Battle & Combat tab
7. **Session 7**: Players & Advanced tabs
8. **Session 8**: Developer Tools tabs
9. **Session 9**: Polish, validation, testing
10. **Session 10**: Migration and deployment

Each session should produce working, testable code that doesn't break existing functionality.

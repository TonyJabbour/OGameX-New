---
trigger: file_match
patterns:
    - "database/migrations/**/*.php"
    - "app/Models/**/*.php"
description: Database and model modification rules
---
🗄️ Database Changes - Careful Approach
Migrations:
✅ Create NEW migrations only
✅ Add nullable columns or with defaults
✅ Add indexes for performance
❌ NEVER edit existing migrations
❌ Ask before dropping columns
❌ Ask before changing column types

Models:
Safe additions:
    New accessor/mutator methods (display purposes)
    New query scopes (filtering/sorting)
    Documentation comments
    Requires approval:
    Modifying relationships
    Changing $fillable or $guarded
    Adding calculated attributes affecting gameplay
    Changing attribute casting
Protected Columns:
    Never modify these without approval:
    User IDs, authentication fields
    Resource amounts (metal, crystal, deuterium)
    Fleet data (mission_type, arrival_time, departure_time)
    Timestamps (created_at, updated_at)
    Foreign keys
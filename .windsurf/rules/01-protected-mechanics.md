---
trigger: always
description: Core game mechanics protection - applies to ALL changes

---
⛔ NEVER MODIFY WITHOUT EXPLICIT APPROVAL
Protected Game Systems
Battle engine (/app/GameEngine/)

Resource calculations (production, storage, costs)

Fleet mechanics (speed, fuel, arrival times)

Research/Building requirements (costs, times, dependencies)

Defense calculations

Database migrations (existing files)

Protected Files
text
/app/GameEngine/**
/app/Services/FleetEngine.php
/app/Services/BattleEngine.php
/app/Services/ResourceService.php
/database/migrations/*.php (existing)
/config/ogame.php
Safety Check Required
Before modifying ANY backend code, ask:

text
⚠️ SAFETY CHECK ⚠️
File: [name]
Affects: [mechanic]
Impact: [description]
Proceed? (yes/no)
Wait for explicit "yes" before proceeding.
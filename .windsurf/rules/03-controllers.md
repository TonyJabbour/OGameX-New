---
trigger: file_match
patterns: "app/Http/Controllers/**/*.php"
description: Controller modification guidelines
---
⚠️ Controller Changes - Ask First
Safe Changes (presentation logic):
php
// ✅ SAFE: Only view changes
public function index()
{
    $data = auth()->user()->planets;
    return view('dashboard', compact('data'));
}
Unsafe Changes (game logic):
php
// ❌ STOP: Modifying game state
public function index()
{
    $planet->metal += 1000; // ← Ask user first!
    $planet->save();
}
Rules:
Controllers should be THIN

Business logic belongs in Services

Always validate with Form Requests

Never modify resources directly

Ask before changing calculations

When to Ask:
Modifying resource amounts

Changing fleet calculations

Altering research/building logic

Any database writes affecting game state
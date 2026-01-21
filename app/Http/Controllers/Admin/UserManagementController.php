<?php

namespace OGame\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use OGame\Http\Controllers\OGameController;
use OGame\Models\User;
use OGame\Models\Planet;
use OGame\Services\PlayerService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends OGameController
{
    /**
     * Shows the user management page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');
        
        $query = User::query();
        
        // Search by username or email
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($filter === 'active') {
            $sevenDaysAgo = (string)(time() - (7 * 24 * 60 * 60));
            $query->where('time', '>=', $sevenDaysAgo);
        } elseif ($filter === 'inactive') {
            $sevenDaysAgo = (string)(time() - (7 * 24 * 60 * 60));
            $query->where('time', '<', $sevenDaysAgo);
        } elseif ($filter === 'vacation') {
            $query->where('vacation_mode', true);
        }
        
        $users = $query->orderBy('id', 'desc')->paginate(50);
        
        // Get statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('time', '>=', (string)(time() - (7 * 24 * 60 * 60)))->count(),
            'vacation' => User::where('vacation_mode', true)->count(),
        ];
        
        return view('admin.users.index')->with([
            'users' => $users,
            'stats' => $stats,
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    /**
     * Shows a specific user's details.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $user = User::findOrFail($id);
        $planets = Planet::where('user_id', $id)->get();
        $dmTransactions = \OGame\Models\DarkMatterTransaction::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('admin.users.show-modern')->with([
            'user' => $user,
            'planets' => $planets,
            'dmTransactions' => $dmTransactions,
        ]);
    }

    /**
     * Updates a user's information.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $action = $request->input('action', 'update_account');
        $isAjax = $request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        switch ($action) {
            case 'update_roles':
                // Update roles
                $user->syncRoles([]);
                if ($request->has('role_admin')) {
                    $user->assignRole('admin');
                }
                if ($request->has('role_moderator')) {
                    $user->assignRole('moderator');
                }
                
                if ($isAjax) {
                    return response()->json(['success' => true, 'message' => 'Roles updated successfully']);
                }
                return redirect()->back()->with('success', 'Roles updated successfully');
                
            case 'update_class':
                // Update character class
                $classValue = $request->input('character_class');
                $user->character_class = $classValue === '' ? null : (int)$classValue;
                $user->character_class_changed_at = now();
                
                if ($request->has('reset_class_free_use')) {
                    $user->character_class_free_used = false;
                }
                
                $user->save();
                
                if ($isAjax) {
                    return response()->json(['success' => true, 'message' => 'Character class updated successfully']);
                }
                return redirect()->back()->with('success', 'Character class updated successfully');
                
            case 'add_dark_matter':
                // Add/subtract dark matter with transaction logging
                $amount = (int)$request->input('dm_amount', 0);
                $description = $request->input('dm_description', 'Admin adjustment');
                
                $oldBalance = $user->dark_matter;
                $user->dark_matter = max(0, $user->dark_matter + $amount);
                $user->save();
                
                // Log transaction
                \OGame\Models\DarkMatterTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'type' => 'admin_adjustment',
                    'description' => $description,
                    'balance_after' => $user->dark_matter,
                    'created_at' => now(),
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => "Dark matter updated: {$oldBalance} → {$user->dark_matter}",
                        'new_balance' => $user->dark_matter,
                        'amount' => $amount
                    ]);
                }
                return redirect()->back()->with('success', "Dark matter updated: {$oldBalance} → {$user->dark_matter}");
                
            default:
                // Update account information
                $validated = $request->validate([
                    'username' => 'nullable|string|min:3|max:20|unique:users,username,' . $id,
                    'email' => 'nullable|email|unique:users,email,' . $id,
                    'dark_matter' => 'nullable|integer|min:0',
                    'vacation_mode' => 'nullable|boolean',
                ]);
                
                if ($request->filled('username')) {
                    $user->username = $validated['username'];
                }
                
                if ($request->filled('email')) {
                    $user->email = $validated['email'];
                }
                
                if ($request->filled('dark_matter')) {
                    $user->dark_matter = $validated['dark_matter'];
                }
                
                if ($request->has('vacation_mode')) {
                    $user->vacation_mode = $request->boolean('vacation_mode');
                    if ($user->vacation_mode) {
                        $user->vacation_mode_activated_at = now();
                        $user->vacation_mode_until = now()->addHours(48);
                    } else {
                        $user->vacation_mode_activated_at = null;
                        $user->vacation_mode_until = null;
                    }
                }
                
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->input('password'));
                }
                
                $user->save();
                
                return redirect()->back()->with('success', 'User updated successfully');
        }
    }

    /**
     * Deletes a user and all related data.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete your own account');
        }
        
        DB::beginTransaction();
        
        try {
            // 1. Clear references to avoid foreign key constraint violations
            $user->planet_current = null;
            $user->alliance_id = null;
            $user->save();
            
            // 2. Delete fleet missions (both from and to this user's planets)
            $planetIds = Planet::where('user_id', $id)->pluck('id')->toArray();
            if (!empty($planetIds)) {
                \OGame\Models\FleetMission::whereIn('planet_id_from', $planetIds)
                    ->orWhereIn('planet_id_to', $planetIds)
                    ->delete();
            }
            
            // 3. Delete fleet unions created by user
            \OGame\Models\FleetUnion::where('user_id', $id)->delete();
            
            // 4. Delete fleet templates
            \OGame\Models\FleetTemplate::where('user_id', $id)->delete();
            
            // 5. Delete messages (sent and received)
            \OGame\Models\Message::where('sender_user_id', $id)
                ->orWhere('receiver_user_id', $id)
                ->delete();
            
            // 6. Delete notes
            \OGame\Models\Note::where('user_id', $id)->delete();
            
            // 7. Delete espionage reports
            \OGame\Models\EspionageReport::where('planet_user_id', $id)->delete();
            
            // 8. Update battle reports (set user_id to null as per migration)
            \OGame\Models\BattleReport::where('planet_user_id', $id)
                ->update(['planet_user_id' => null]);
            
            // 9. Delete buddy requests (sent and received)
            \OGame\Models\BuddyRequest::where('sender_user_id', $id)
                ->orWhere('receiver_user_id', $id)
                ->delete();
            
            // 10. Delete ignored players
            \OGame\Models\IgnoredPlayer::where('user_id', $id)
                ->orWhere('ignored_user_id', $id)
                ->delete();
            
            // 11. Delete alliance applications
            \OGame\Models\AllianceApplication::where('user_id', $id)->delete();
            
            // 12. Delete alliance membership
            \OGame\Models\AllianceMember::where('user_id', $id)->delete();
            
            // 13. Handle alliances founded by this user (delete them)
            $foundedAlliances = \OGame\Models\Alliance::where('founder_user_id', $id)->get();
            foreach ($foundedAlliances as $alliance) {
                \OGame\Models\AllianceMember::where('alliance_id', $alliance->id)->delete();
                \OGame\Models\AllianceApplication::where('alliance_id', $alliance->id)->delete();
                \OGame\Models\AllianceRank::where('alliance_id', $alliance->id)->delete();
                \OGame\Models\AllianceHighscore::where('alliance_id', $alliance->id)->delete();
                $alliance->delete();
            }
            
            // 14. Delete dark matter transactions
            \OGame\Models\DarkMatterTransaction::where('user_id', $id)->delete();
            
            // 15. Delete merchant calls
            \OGame\Models\MerchantCall::where('user_id', $id)->delete();
            
            // 16. Delete user highscore
            \OGame\Models\Highscore::where('player_id', $id)->delete();
            
            // 17. Delete user tech
            \OGame\Models\UserTech::where('user_id', $id)->delete();
            
            // 18. Delete all user's planets (cascades to building/research/unit queues)
            Planet::where('user_id', $id)->delete();
            
            // 19. Clear any references to this user as banner
            User::where('banned_by_user_id', $id)->update(['banned_by_user_id' => null]);
            
            // 20. Delete the user
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.users.index')->with('success', 'User and all related data deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Ban a user.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function ban(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        
        // Prevent banning yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot ban your own account');
        }
        
        // Prevent banning admins (unless you're also admin)
        if ($user->hasRole('admin') && !auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Cannot ban administrators');
        }
        
        $validated = $request->validate([
            'ban_reason' => 'required|string|max:500',
            'ban_duration' => 'required|in:permanent,1day,3days,7days,30days',
        ]);
        
        $bannedUntil = null;
        if ($validated['ban_duration'] !== 'permanent') {
            $days = match($validated['ban_duration']) {
                '1day' => 1,
                '3days' => 3,
                '7days' => 7,
                '30days' => 30,
            };
            $bannedUntil = now()->addDays($days);
        }
        
        $user->ban($validated['ban_reason'], $bannedUntil, auth()->id());
        
        $durationText = $bannedUntil ? "until {$bannedUntil->format('Y-m-d H:i')}" : 'permanently';
        return redirect()->back()->with('success', "User {$user->username} has been banned {$durationText}");
    }

    /**
     * Unban a user.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function unban(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        
        if (!$user->is_banned) {
            return redirect()->back()->with('error', 'User is not banned');
        }
        
        $user->unban();
        
        return redirect()->back()->with('success', "User {$user->username} has been unbanned");
    }
}

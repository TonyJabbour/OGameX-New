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
     * Deletes a user.
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
        
        // Delete all user's planets
        Planet::where('user_id', $id)->delete();
        
        // Delete user
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}

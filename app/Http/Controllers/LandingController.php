<?php

namespace OGame\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use OGame\Models\User;
use OGame\Models\Planet;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Show the landing page
     * 
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // If user is already authenticated, redirect to overview
        if (Auth::check()) {
            return redirect()->route('overview.index');
        }

        // Get statistics for display on landing page
        // The 'time' field stores Unix timestamp as string for last user activity
        $sevenDaysAgo = (string)(time() - (7 * 24 * 60 * 60));
        
        // Get battles from last 24 hours
        // battle_reports table uses created_at timestamp column
        $oneDayAgo = now()->subDay();
        
        $stats = [
            'active_players' => User::where('time', '>=', $sevenDaysAgo)->count(),
            'total_players' => User::count(),
            'planets_colonized' => Planet::where('planet_type', 1)->count(), // Count only planets (type 1), not moons (type 3)
            'battles_today' => DB::table('battle_reports')
                ->where('created_at', '>=', $oneDayAgo)
                ->count(),
        ];

        return view('landing.index', compact('stats'));
    }

    /**
     * Show the login page with modern design
     * 
     * @return View
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('overview.index');
        }

        return view('auth.login');
    }

    /**
     * Show the register page with modern design
     * 
     * @return View
     */
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('overview.index');
        }

        return view('auth.register');
    }
}

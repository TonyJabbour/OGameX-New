<?php

namespace OGame\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OGame\Http\Controllers\Controller;
use OGame\Models\User;

class AuthCheckController extends Controller
{
    /**
     * Check if email exists in database
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    /**
     * Check if username is available
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function checkUsername(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string|min:3|max:20|regex:/^[a-zA-Z0-9]+$/'
        ]);

        $available = !User::where('username', $request->username)->exists();

        return response()->json([
            'available' => $available
        ]);
    }
}

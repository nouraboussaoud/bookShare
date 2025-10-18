<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified user profile.
     */
    public function show(User $user)
    {
        // Load user with relationships
        $user->load(['books' => function($query) {
            $query->latest()->limit(6);
        }, 'reviews' => function($query) {
            $query->with('book')->latest()->limit(3);
        }]);

        return view('users.show', compact('user'));
    }
}
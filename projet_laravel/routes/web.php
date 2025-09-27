<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserDashboardController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome-bookshare');
});

// Route principale dashboard - redirige selon le rôle
Route::get('/dashboard', function () {
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('pages.dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

// Routes Admin pour la gestion des utilisateurs et catégories
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    
    // Categories management routes
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryManagementController::class);
    Route::patch('categories/{category}/toggle-status', [\App\Http\Controllers\Admin\CategoryManagementController::class, 'toggleStatus'])
        ->name('categories.toggle-status');
    Route::patch('categories/{category}/toggle-featured', [\App\Http\Controllers\Admin\CategoryManagementController::class, 'toggleFeatured'])
        ->name('categories.toggle-featured');
    
    // Admin routes for reviews management
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewManagementController::class);
    Route::patch('reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewManagementController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewManagementController::class, 'reject'])->name('reviews.reject');
});

// Dashboard User -> now uses controller for dynamic data
Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'user'])->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Simple logout test route
    Route::get('/test-logout', function () {
        Auth::logout();
        return redirect('/')->with('message', 'Logged out successfully via test route');
    })->name('test.logout');
    
    // Route de debug pour vérifier/changer le rôle (TEMPORAIRE)
    Route::get('/debug-role', function () {
        $user = Auth::user();
        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'current_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_user' => $user->isUser()
        ]);
    });
    
    // Route pour changer le rôle en admin (TEMPORAIRE - à supprimer en production)
    Route::get('/make-admin', function () {
        $user = Auth::user();
        $user->role = 'admin';
        $user->save();
        return redirect('/dashboard')->with('message', 'Vous êtes maintenant administrateur!');
    });


    // Books resource routes
    Route::resource('books', BookController::class);
    // Toggle book status (AVAILABLE <-> RESERVED)
    Route::patch('books/{book}/toggle-status', [BookController::class, 'toggleStatus'])->name('books.toggleStatus');
    
    // Reviews resource routes - Users can manage their own reviews
    Route::resource('reviews', ReviewController::class);
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\Admin\ExchangeAdminController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\LocationController;

use App\Http\Controllers\ReadingGroupController;
use App\Http\Controllers\GroupMembershipController;


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
Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

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
    
    // Admin routes for reports management
    Route::resource('reports', \App\Http\Controllers\Admin\ReportAdminController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reports/{report}/status', [\App\Http\Controllers\Admin\ReportAdminController::class, 'updateStatus'])->name('reports.updateStatus');
    Route::post('reports/bulk-status', [\App\Http\Controllers\Admin\ReportAdminController::class, 'bulkUpdateStatus'])->name('reports.bulkUpdateStatus');
});

// Dashboard User -> now uses controller for dynamic data
Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'user'])->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notification routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::get('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
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

Route::resource('locations', LocationController::class);
    
    // Routes spécifiques pour les actions de location
    Route::post('locations/{location}/confirmer', [LocationController::class, 'confirmer'])->name('locations.confirmer');
    Route::post('locations/{location}/refuser', [LocationController::class, 'refuser'])->name('locations.refuser');
    Route::post('locations/{location}/demarrer', [LocationController::class, 'demarrer'])->name('locations.demarrer');
    Route::post('locations/{location}/terminer', [LocationController::class, 'terminer'])->name('locations.terminer');
    
    // Route pour le marketplace des locations
    Route::get('locations-marketplace', [LocationController::class, 'marketplace'])->name('locations.marketplace');
    
    // Route pour l'aide des locations
    Route::get('locations-help', function () {
        return view('locations.help');
    })->name('locations.help');
    // Books resource routes
    Route::resource('books', BookController::class);
    // Toggle book status (AVAILABLE <-> RESERVED)
    Route::patch('books/{book}/toggle-status', [BookController::class, 'toggleStatus'])->name('books.toggleStatus');
    
    // Reviews resource routes - Users can manage their own reviews
    Route::resource('reviews', ReviewController::class);
    
    // Report routes for users
    Route::resource('reports', \App\Http\Controllers\ReportController::class)->only(['index', 'create', 'store', 'show']);
    
// -----------------------
// Reading Groups
// -----------------------
// Add this to enable the create view/route
Route::get('reading-groups/create', [ReadingGroupController::class, 'create'])->name('reading-groups.create');

// Full CRUD (index, store, show, update, destroy)
// excluding only create/edit since you don’t use blade forms
Route::resource('reading-groups', ReadingGroupController::class)
    ->except(['create','edit']);
 Route::get('reading-groups/{readingGroup}/edit', [ReadingGroupController::class, 'edit'])->name('reading-groups.edit');
// Membership actions (join / leave)
Route::post('reading-groups/{readingGroup}/join', [GroupMembershipController::class, 'join'])
    ->name('reading-groups.join');
Route::delete('reading-groups/{readingGroup}/leave', [GroupMembershipController::class, 'leave'])
    ->name('reading-groups.leave');


});

// Routes Front Office
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::post('/reserve-book', [ExchangeController::class, 'reserveBook'])->name('user.reserveBook');
    Route::patch('/confirm-exchange/{id}', [ExchangeController::class, 'confirmExchange'])->name('user.confirmExchange');
    Route::get('/exchange-history', [ExchangeController::class, 'exchangeHistory'])->name('user.exchangeHistory');
});

// Routes Back Office
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Exchange management routes
    Route::resource('exchanges', ExchangeAdminController::class);
    Route::patch('/exchanges/{exchange}/supervise', [ExchangeAdminController::class, 'supervise'])->name('exchanges.supervise');
    Route::patch('/exchanges/{exchange}/arbitrate', [ExchangeAdminController::class, 'arbitrate'])->name('exchanges.arbitrate');
    Route::patch('/exchanges/{exchange}/cancel', [ExchangeAdminController::class, 'cancel'])->name('exchanges.cancel');
    
    // Legacy API routes
    Route::get('/supervise-exchanges', [ExchangeAdminController::class, 'superviseExchanges'])->name('superviseExchanges');
    Route::patch('/arbitrate-exchange/{id}', [ExchangeAdminController::class, 'arbitrateExchange'])->name('arbitrateExchange');
    Route::delete('/cancel-exchange/{id}', [ExchangeAdminController::class, 'cancelExchange'])->name('cancelExchange');
});

// Route for creating an exchange
Route::get('/exchanges/create', [ExchangeController::class, 'create'])->name('exchanges.create');

// Route for user profiles
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Route for listing exchanges
Route::get('/exchanges', [ExchangeController::class, 'index'])->name('exchanges.index');

// Route for storing a new exchange
Route::post('/exchanges', [ExchangeController::class, 'store'])->name('exchanges.store');
// Route for showing a specific exchange
Route::get('/exchanges/{exchange}', [ExchangeController::class, 'show'])->name('exchanges.show');
// Route for editing an exchange
Route::get('/exchanges/{exchange}/edit', [ExchangeController::class, 'edit'])->name('exchanges.edit');
// Route for updating an exchange
Route::put('/exchanges/{exchange}', [ExchangeController::class, 'update'])->name('exchanges.update');
// Routes for accepting and rejecting exchanges
Route::post('/exchanges/{exchange}/accept', [ExchangeController::class, 'accept'])->name('exchanges.accept');
Route::post('/exchanges/{exchange}/reject', [ExchangeController::class, 'reject'])->name('exchanges.reject');

require __DIR__.'/auth.php';

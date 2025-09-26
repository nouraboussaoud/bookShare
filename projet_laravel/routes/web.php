<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\Admin\ExchangeAdminController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Routes Admin pour la gestion des utilisateurs
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])
        ->name('users.toggle-status');
});

// Dashboard User
Route::get('/user/dashboard', [ExchangeController::class, 'userDashboard'])->middleware(['auth', 'user'])->name('user.dashboard');

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

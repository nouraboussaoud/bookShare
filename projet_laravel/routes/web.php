<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
<<<<<<< Updated upstream
=======
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\LocationController;
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripePaymentController;

use App\Http\Controllers\ReadingGroupController;
use App\Http\Controllers\GroupMembershipController;
use App\Http\Controllers\ReadingProgressController;

>>>>>>> Stashed changes

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

// Routes Admin pour la gestion des utilisateurs
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])
        ->name('users.toggle-status');
});

// Dashboard User
Route::get('/user/dashboard', function () {
    return view('pages.user-dashboard');
})->middleware(['auth', 'user'])->name('user.dashboard');

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
<<<<<<< Updated upstream
=======


    // Books resource routes
    Route::resource('books', BookController::class);
    // Toggle book status (AVAILABLE <-> RESERVED)
    Route::patch('books/{book}/toggle-status', [BookController::class, 'toggleStatus'])->name('books.toggleStatus');
    
    // Reviews resource routes - Users can manage their own reviews
    Route::resource('reviews', ReviewController::class);
    
    // Location resource routes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
});

=======

    // Routes pour les paiements de réservation
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::post('payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
    
    // Routes Stripe pour les paiements
    Route::post('stripe/checkout/{payment}', [StripePaymentController::class, 'createCheckoutSession'])->name('stripe.checkout');
    Route::get('stripe/success/{payment}', [StripePaymentController::class, 'success'])->name('stripe.success');
    Route::get('stripe/cancel/{payment}', [StripePaymentController::class, 'cancel'])->name('stripe.cancel');
    
    // Books resource routes
    Route::resource('books', BookController::class);
    // Toggle book status (AVAILABLE <-> RESERVED)
    Route::patch('books/{book}/toggle-status', [BookController::class, 'toggleStatus'])->name('books.toggleStatus');
    // AI Summary generation
    Route::post('books/{book}/generate-summary', [BookController::class, 'generateSummary'])->name('books.generateSummary');
    // Categories resource routes
    Route::resource('categories', CategoryController::class);
    
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
// excluding only create/edit since you don't use blade forms
Route::resource('reading-groups', ReadingGroupController::class)
    ->except(['create','edit']);
Route::get('reading-groups/{readingGroup}/edit', [ReadingGroupController::class, 'edit'])->name('reading-groups.edit');

// Membership actions (join / leave)
Route::post('reading-groups/{readingGroup}/join', [GroupMembershipController::class, 'join'])
    ->name('reading-groups.join');
Route::delete('reading-groups/{readingGroup}/leave', [GroupMembershipController::class, 'leave'])
    ->name('reading-groups.leave');

// Member management (owner only)
Route::middleware(['auth'])->group(function () {
    Route::post('reading-groups/{readingGroup}/members/{membership}/approve', [GroupMembershipController::class, 'approve'])
        ->name('reading-groups.memberships.approve');
    Route::post('reading-groups/{readingGroup}/members/{membership}/reject', [GroupMembershipController::class, 'reject'])
        ->name('reading-groups.memberships.reject');
    Route::delete('reading-groups/{readingGroup}/members/{userId}', [GroupMembershipController::class, 'remove'])
        ->name('reading-groups.members.remove');
    Route::patch('reading-groups/{readingGroup}/members/{userId}/role', [GroupMembershipController::class, 'changeRole'])
        ->name('reading-groups.members.changeRole');
    Route::get('reading-groups/{readingGroup}/members-list', [GroupMembershipController::class, 'getMembersList'])
        ->name('reading-groups.members.list');
});

// Group Events routes
Route::middleware(['auth'])->group(function () {
    Route::get('reading-groups/{readingGroup}/events', [\App\Http\Controllers\GroupEventController::class, 'index'])
        ->name('reading-groups.events.index');
    Route::get('reading-groups/{readingGroup}/events/create', [\App\Http\Controllers\GroupEventController::class, 'create'])
        ->name('reading-groups.events.create');
    Route::post('reading-groups/{readingGroup}/events', [\App\Http\Controllers\GroupEventController::class, 'store'])
        ->name('reading-groups.events.store');
    Route::get('reading-groups/{readingGroup}/events/{event}', [\App\Http\Controllers\GroupEventController::class, 'show'])
        ->name('reading-groups.events.show');
    Route::get('reading-groups/{readingGroup}/events/{event}/edit', [\App\Http\Controllers\GroupEventController::class, 'edit'])
        ->name('reading-groups.events.edit');
    Route::put('reading-groups/{readingGroup}/events/{event}', [\App\Http\Controllers\GroupEventController::class, 'update'])
        ->name('reading-groups.events.update');
    Route::delete('reading-groups/{readingGroup}/events/{event}', [\App\Http\Controllers\GroupEventController::class, 'destroy'])
        ->name('reading-groups.events.destroy');
    
    // Event attendance
    Route::post('reading-groups/{readingGroup}/events/{event}/join', [\App\Http\Controllers\GroupEventController::class, 'joinEvent'])
        ->name('reading-groups.events.join');
    Route::delete('reading-groups/{readingGroup}/events/{event}/leave', [\App\Http\Controllers\GroupEventController::class, 'leaveEvent'])
        ->name('reading-groups.events.leave');
    
    // Event Chat Routes
    Route::get('events/{event}/chat/messages', [\App\Http\Controllers\EventChatController::class, 'getMessages'])
        ->name('events.chat.messages');
    Route::post('events/{event}/chat/messages', [\App\Http\Controllers\EventChatController::class, 'postMessage'])
        ->name('events.chat.post');
    Route::post('events/{event}/chat/typing', [\App\Http\Controllers\EventChatController::class, 'updateTypingStatus'])
        ->name('events.chat.typing');
    Route::get('events/{event}/chat/typing', [\App\Http\Controllers\EventChatController::class, 'getTypingStatus'])
        ->name('events.chat.typing-status');
    Route::delete('events/{event}/chat/messages/{eventChatMessage}', [\App\Http\Controllers\EventChatController::class, 'deleteMessage'])
        ->name('events.chat.delete');
    Route::get('events/{event}/chat/statistics', [\App\Http\Controllers\EventChatController::class, 'getStatistics'])
        ->name('events.chat.statistics');
    Route::get('events/{event}/chat', [\App\Http\Controllers\EventChatController::class, 'showChat'])
        ->name('events.chat.show');
});

// -----------------------
// Reading Progress Routes
// -----------------------
// Liste des progressions de lecture
Route::get('reading-progress', [ReadingProgressController::class, 'index'])->name('reading-progress.index');

// Statistiques de lecture
Route::get('reading-progress/statistics', [ReadingProgressController::class, 'statistics'])->name('reading-progress.statistics');

// Afficher une progression spécifique
Route::get('reading-progress/{readingProgress}', [ReadingProgressController::class, 'show'])->name('reading-progress.show');

// Créer une nouvelle progression
Route::post('reading-progress', [ReadingProgressController::class, 'store'])->name('reading-progress.store');

// Mettre à jour une progression
Route::put('reading-progress/{readingProgress}', [ReadingProgressController::class, 'update'])->name('reading-progress.update');
Route::patch('reading-progress/{readingProgress}', [ReadingProgressController::class, 'update']);

// Supprimer une progression
Route::delete('reading-progress/{readingProgress}', [ReadingProgressController::class, 'destroy'])->name('reading-progress.destroy');

// Actions rapides
Route::post('reading-progress/{readingProgress}/add-time', [ReadingProgressController::class, 'addReadingTime'])->name('reading-progress.addTime');
Route::post('reading-progress/{readingProgress}/complete', [ReadingProgressController::class, 'markAsCompleted'])->name('reading-progress.complete');
Route::post('books/{book}/mark-to-read', [ReadingProgressController::class, 'markAsToRead'])->name('books.markToRead');
Route::post('books/{book}/start-reading', [ReadingProgressController::class, 'startReading'])->name('books.startReading');

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

    // Location (Réservations) management routes
    Route::get('locations', [\App\Http\Controllers\Admin\LocationAdminController::class, 'index'])->name('locations.index');
    Route::get('locations/{location}', [\App\Http\Controllers\Admin\LocationAdminController::class, 'show'])->name('locations.show');
    Route::patch('locations/{location}/approve', [\App\Http\Controllers\Admin\LocationAdminController::class, 'approve'])->name('locations.approve');
    Route::patch('locations/{location}/reject', [\App\Http\Controllers\Admin\LocationAdminController::class, 'reject'])->name('locations.reject');
    Route::delete('locations/{location}', [\App\Http\Controllers\Admin\LocationAdminController::class, 'destroy'])->name('locations.destroy');

    // Payment management routes
    Route::get('payments', [PaymentController::class, 'adminIndex'])->name('payments.index');

    // Admin Groupes management (minimal CRUD view/edit/delete)
    Route::get('groupes', [\App\Http\Controllers\Admin\GroupManagementController::class, 'index'])->name('groupes.index');
    Route::get('groupes/{readingGroup}', [\App\Http\Controllers\Admin\GroupManagementController::class, 'show'])->name('groupes.show');
    Route::get('groupes/{readingGroup}/edit', [\App\Http\Controllers\Admin\GroupManagementController::class, 'edit'])->name('groupes.edit');
    Route::put('groupes/{readingGroup}', [\App\Http\Controllers\Admin\GroupManagementController::class, 'update'])->name('groupes.update');
    Route::delete('groupes/{readingGroup}', [\App\Http\Controllers\Admin\GroupManagementController::class, 'destroy'])->name('groupes.destroy');

    // Admin Événements management (minimal CRUD view/edit/delete)
    Route::get('evenements', [\App\Http\Controllers\Admin\EventManagementController::class, 'index'])->name('evenements.index');
    Route::get('evenements/{groupEvent}', [\App\Http\Controllers\Admin\EventManagementController::class, 'show'])->name('evenements.show');
    Route::get('evenements/{groupEvent}/edit', [\App\Http\Controllers\Admin\EventManagementController::class, 'edit'])->name('evenements.edit');
    Route::put('evenements/{groupEvent}', [\App\Http\Controllers\Admin\EventManagementController::class, 'update'])->name('evenements.update');
    Route::delete('evenements/{groupEvent}', [\App\Http\Controllers\Admin\EventManagementController::class, 'destroy'])->name('evenements.destroy');
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

// Routes API pour l'Intelligence Artificielle
Route::middleware('auth')->prefix('api')->group(function () {
    // Routes pour la classification automatique des signalements
    Route::post('/classify-report', [\App\Http\Controllers\AIReportController::class, 'classifyReport'])->name('api.classify-report');
    Route::get('/test-ai-connection', [\App\Http\Controllers\AIReportController::class, 'testConnection'])->name('api.test-ai-connection');
    
    // Routes pour les recommandations intelligentes d'échanges
    Route::get('/recommend-books/{book}', [\App\Http\Controllers\AIRecommendationController::class, 'recommendBooks'])->name('api.recommend-books');
    
    // Routes pour le chatbot
    Route::post('/chatbot/message', [\App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('api.chatbot.message');
    Route::get('/chatbot/suggestions', [\App\Http\Controllers\ChatbotController::class, 'getSuggestions'])->name('api.chatbot.suggestions');
    Route::delete('/chatbot/history', [\App\Http\Controllers\ChatbotController::class, 'clearHistory'])->name('api.chatbot.history');
});

// Route de test IA (sans auth pour le debug)
Route::get('/test-ai', function () {
    return view('test-ai');
})->name('test.ai');

// Webhook Stripe (sans middleware auth car Stripe envoie les requêtes)
Route::post('stripe/webhook', [StripePaymentController::class, 'webhook'])->name('stripe.webhook');

>>>>>>> Stashed changes
require __DIR__.'/auth.php';

<?php

namespace App\Providers;

use App\Models\GroupEvent;
use App\Observers\GroupEventObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        
        // Register observer for automatic event reminders and poll auto-close
        GroupEvent::observe(GroupEventObserver::class);
        
        // Register observers for event scheduling
        GroupEvent::observe(GroupEventObserver::class);
    }
}

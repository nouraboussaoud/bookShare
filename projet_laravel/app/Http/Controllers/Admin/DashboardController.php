<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for dashboard
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $adminUsers = User::where('role', 'admin')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        
        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();
        
        // Get ongoing exchanges for admin management
        $ongoingExchanges = Exchange::with(['initiateur', 'recepteur', 'bookDemande'])
            ->whereIn('status', ['EN_ATTENTE', 'EN_COURS'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('pages.dashboard', compact(
            'totalUsers',
            'activeUsers', 
            'adminUsers',
            'inactiveUsers',
            'recentUsers',
            'ongoingExchanges'
        ));
    }
}

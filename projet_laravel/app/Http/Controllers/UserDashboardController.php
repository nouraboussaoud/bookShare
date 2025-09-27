<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user');
    }

    public function index()
    {
        $user = Auth::user();

        $myBooksCount = Book::where('user_id', $user->id)->count();
        $recentMyBooks = Book::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        $recommendations = Book::where('user_id', '!=', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('pages.user-dashboard', compact('myBooksCount', 'recentMyBooks', 'recommendations'));
    }
}

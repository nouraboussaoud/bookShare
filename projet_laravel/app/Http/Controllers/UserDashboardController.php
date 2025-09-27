<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters
        $categoryId = $request->get('category');
        $ageFilter = $request->get('age');
        $search = $request->get('search');

        // Statistics
        $myBooksCount = Book::where('user_id', $user->id)->count();
        $totalBooksCount = Book::count();
        $availableBooksCount = Book::where('status', 'AVAILABLE')->count();

        // Recent user books
        $recentMyBooks = Book::where('user_id', $user->id)
            ->with(['category', 'review'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Available books with filters
        $booksQuery = Book::where('user_id', '!=', $user->id)
            ->where('status', 'AVAILABLE')
            ->with(['user', 'category', 'reviews']);

        // Apply filters
        if ($categoryId) {
            $booksQuery->where('category_id', $categoryId);
        }

        if ($ageFilter) {
            $booksQuery->where('recommended_age', '<=', $ageFilter);
        }

        if ($search) {
            $booksQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $availableBooks = $booksQuery->orderByDesc('created_at')->paginate(12);

        // Categories for filter (simplified - remove method calls that might not exist)
        $categories = Category::orderBy('name')->get();

        // Featured categories (simplified)
        $featuredCategories = Category::orderBy('name')->limit(6)->get();

        return view('pages.user-dashboard', compact(
            'myBooksCount', 
            'totalBooksCount', 
            'availableBooksCount',
            'recentMyBooks', 
            'availableBooks',
            'categories',
            'featuredCategories',
            'categoryId',
            'ageFilter',
            'search'
        ));
    }
}

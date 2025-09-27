<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reviews = Review::with(['book.user', 'book.category'])
            ->latest()
            ->paginate(10);
        
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $bookId = $request->get('book_id');
        $book = null;
        
        if ($bookId) {
            $book = Book::with(['user', 'category'])->findOrFail($bookId);
            
            // Check if user already reviewed this book
            $existingReview = Review::where('book_id', $bookId)->first();
            if ($existingReview) {
                return redirect()->route('books.show', $book)
                    ->with('error', 'This book already has a review.');
            }
        }
        
        $books = Book::with(['user', 'category'])->get();
        return view('reviews.create', compact('books', 'book'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if book already has a review
        $existingReview = Review::where('book_id', $validated['book_id'])->first();
        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'This book already has a review.');
        }

        $validated['status'] = 'PENDING';
        $validated['user_id'] = Auth::id();
        
        Review::create($validated);

        return redirect()->route('user.dashboard')
            ->with('success', 'Votre avis a été soumis avec succès ! Il sera visible après validation par un administrateur.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): View
    {
        $review->load(['book.user', 'book.category']);
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review): View
    {
        // Users can only edit their own reviews, admins can edit any review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own reviews.');
        }
        
        $review->load(['book.user', 'book.category']);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        // Users can only update their own reviews, admins can update any review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403, 'You can only update your own reviews.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        // Users can only delete their own reviews, admins can delete any review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own reviews.');
        }

        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Approve a review (Admin only)
     */
    public function approve(Review $review, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_reply' => 'nullable|string|max:500',
        ]);

        $review->update([
            'status' => 'APPROVED',
            'admin_reply' => $validated['admin_reply'] ?? null,
        ]);

        return redirect()->route('reviews.index')
            ->with('success', 'Review approved successfully.');
    }

    /**
     * Reject a review (Admin only)
     */
    public function reject(Review $review, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:500',
        ]);

        $review->update([
            'status' => 'REJECTED',
            'admin_reply' => $validated['admin_reply'],
        ]);

        return redirect()->route('reviews.index')
            ->with('success', 'Review rejected successfully.');
    }
}

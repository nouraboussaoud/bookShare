<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewManagementController extends Controller
{
    /**
     * Display a listing of all reviews for admin management.
     */
    public function index(): View
    {
        $reviews = Review::with(['book.user', 'book.category', 'user'])
            ->latest()
            ->paginate(15);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review (admin can create on behalf of users).
     */
    public function create(): View
    {
        $books = \App\Models\Book::with(['user', 'category'])->get();
        $users = \App\Models\User::where('role', 'user')->get();
        return view('admin.reviews.create', compact('books', 'users'));
    }

    /**
     * Store a newly created review (admin creating on behalf of user).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'status' => 'required|in:PENDING,APPROVED,REJECTED',
            'admin_reply' => 'nullable|string|max:500',
        ]);

        // Check if book already has a review
        $existingReview = Review::where('book_id', $validated['book_id'])->first();
        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'This book already has a review.');
        }

        Review::create($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): View
    {
        $review->load(['book.user', 'book.category', 'user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review): View
    {
        $review->load(['book.user', 'book.category', 'user']);
        $users = \App\Models\User::where('role', 'user')->get();
        return view('admin.reviews.edit', compact('review', 'users'));
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'status' => 'required|in:PENDING,APPROVED,REJECTED',
            'admin_reply' => 'nullable|string|max:500',
        ]);

        $review->update($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Approve a review.
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

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review approved successfully.');
    }

    /**
     * Reject a review.
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

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review rejected successfully.');
    }
}

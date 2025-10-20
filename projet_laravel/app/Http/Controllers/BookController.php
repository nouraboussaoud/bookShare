<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\BookSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // Middleware is applied at route level, no need for constructor

    public function index(Request $request)
    {
        $user = Auth::user();
        $scope = $request->query('scope'); // null | 'others'

        $query = Book::query()->with(['user', 'category', 'reviews']);

        if ($user->isAdmin()) {
            // Admin: show all by default, but allow filtering to others if requested
            if ($scope === 'others') {
                $query->where('user_id', '!=', $user->id);
            }
        } else {
            // Normal user: default to own books; if scope=others, show community books
            if ($scope === 'others') {
                $query->where('user_id', '!=', $user->id);
            } else {
                $query->where('user_id', $user->id);
            }
        }

        $books = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('books.index', compact('books', 'scope'));
    }

    public function create()
    {
        $categories = Category::with('categoryTags')->orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function show(Book $book)
    {
        $book->load(['user', 'category', 'review', 'categoryTags']);
        return view('books.show', compact('book'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:available,reserved'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'recommended_age' => ['required', 'integer', 'min:0', 'max:18'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        // Handle image upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('books', 'public');
        }

        $validated['user_id'] = Auth::id();
        $book = Book::create($validated);

        // Attach tags if provided
        if ($request->has('tags')) {
            $book->categoryTags()->attach($request->tags, ['created_by_user_id' => Auth::id()]);
            
            // Update usage count for each tag
            foreach ($request->tags as $tagId) {
                \App\Models\CategoryTag::find($tagId)?->incrementUsage();
            }
        }

        return redirect()->route('user.dashboard')->with('success', 'Livre créé avec succès.');
    }

    public function edit(Book $book)
    {
        $this->authorizeAccess($book);
        $categories = Category::with('categoryTags')->orderBy('name')->get();
        $book->load('categoryTags');
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorizeAccess($book);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:available,reserved'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'recommended_age' => ['required', 'integer', 'min:0', 'max:18'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        // Handle image upload
        if ($request->hasFile('photo')) {
            // Delete old image if exists
            if ($book->photo) {
                \Storage::disk('public')->delete($book->photo);
            }
            $validated['photo'] = $request->file('photo')->store('books', 'public');
        }

        $book->update($validated);

        // Sync tags if provided
        if ($request->has('tags')) {
            // Get old tags to decrement their usage
            $oldTags = $book->categoryTags->pluck('id')->toArray();
            
            // Sync tags
            $book->categoryTags()->sync($request->tags);
            
            // Update usage counts
            foreach ($oldTags as $oldTagId) {
                if (!in_array($oldTagId, $request->tags)) {
                    \App\Models\CategoryTag::find($oldTagId)?->decrementUsage();
                }
            }
            
            foreach ($request->tags as $newTagId) {
                if (!in_array($newTagId, $oldTags)) {
                    \App\Models\CategoryTag::find($newTagId)?->incrementUsage();
                }
            }
        } else {
            // Remove all tags if none provided
            foreach ($book->categoryTags as $tag) {
                $tag->decrementUsage();
            }
            $book->categoryTags()->detach();
        }

        return redirect()->route('books.index')->with('success', 'Livre mis à jour avec succès.');
    }

    public function destroy(Book $book)
    {
        $this->authorizeAccess($book);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès.');
    }

    public function toggleStatus(Book $book)
    {
        $this->authorizeAccess($book);
        $book->status = $book->status === 'available' ? 'reserved' : 'available';
        $book->save();

        return redirect()->route('books.index')->with('success', 'Statut du livre mis à jour.');
    }

    public function generateSummary(Book $book, BookSummaryService $summaryService)
    {
        $this->authorizeAccess($book);

        $result = $summaryService->generateSummary($book);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    private function authorizeAccess(Book $book): void
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $book->user_id !== $user->id) {
            abort(403, 'Action non autorisée.');
        }
    }
}

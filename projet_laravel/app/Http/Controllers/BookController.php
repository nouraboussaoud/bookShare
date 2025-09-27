<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $scope = $request->query('scope'); // null | 'others'

        $query = Book::query()->with('user');

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
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:AVAILABLE,RESERVED'],
        ]);

        $validated['user_id'] = Auth::id();
        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Livre créé avec succès.');
    }

    public function edit(Book $book)
    {
        $this->authorizeAccess($book);
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorizeAccess($book);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:AVAILABLE,RESERVED'],
        ]);

        $book->update($validated);
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
        $book->status = $book->status === 'AVAILABLE' ? 'RESERVED' : 'AVAILABLE';
        $book->save();

        return redirect()->route('books.index')->with('success', 'Statut du livre mis à jour.');
    }

    private function authorizeAccess(Book $book): void
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $book->user_id !== $user->id) {
            abort(403, 'Action non autorisée.');
        }
    }
}

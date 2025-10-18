<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::withCount('books')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'age_allowed' => 'required|integer|min:0|max:18',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'reading_tips' => 'nullable|string|max:1000',
            'popular_authors' => 'nullable|array',
            'popular_authors.*' => 'string|max:255',
        ]);

        // Convert popular_authors array to JSON if provided
        if (isset($validated['popular_authors'])) {
            $validated['popular_authors'] = array_filter($validated['popular_authors']);
        }

        // Set defaults
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : true; // Default to active
        $validated['sort_order'] = $validated['sort_order'] ?? 999;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load(['books.user']);
        $booksCount = $category->books()->count();
        $recentBooks = $category->books()->with('user')->latest()->limit(5)->get();
        
        return view('admin.categories.show', compact('category', 'booksCount', 'recentBooks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'age_allowed' => 'required|integer|min:0|max:18',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'reading_tips' => 'nullable|string|max:1000',
            'popular_authors' => 'nullable|array',
            'popular_authors.*' => 'string|max:255',
        ]);

        // Convert popular_authors array to JSON if provided
        if (isset($validated['popular_authors'])) {
            $validated['popular_authors'] = array_filter($validated['popular_authors']);
        }

        // Set checkboxes
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $category->sort_order;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has books
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des livres.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Toggle the active status of a category.
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activée' : 'désactivée';
        
        return redirect()->route('admin.categories.index')
            ->with('success', "Catégorie {$status} avec succès.");
    }

    /**
     * Toggle the featured status of a category.
     */
    public function toggleFeatured(Category $category): RedirectResponse
    {
        $category->update([
            'is_featured' => !$category->is_featured
        ]);

        $status = $category->is_featured ? 'mise en avant' : 'retirée de la mise en avant';
        
        return redirect()->route('admin.categories.index')
            ->with('success', "Catégorie {$status} avec succès.");
    }
}

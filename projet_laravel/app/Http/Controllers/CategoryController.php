<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::withCount('books')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'age_allowed' => 'required|integer|min:0|max:18',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string|max:50',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'reading_tips' => 'nullable|string',
            'popular_authors' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Convert popular_authors string to array
        if (!empty($validated['popular_authors'])) {
            $validated['popular_authors'] = array_map('trim', explode(',', $validated['popular_authors']));
        } else {
            $validated['popular_authors'] = null;
        }

        // Set default values for checkboxes
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : true; // Default to active

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load(['books' => function ($query) {
            $query->with('user')->latest();
        }]);
        
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'age_allowed' => 'required|integer|min:0|max:18',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string|max:50',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
            'reading_tips' => 'nullable|string',
            'popular_authors' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Convert popular_authors string to array
        if (!empty($validated['popular_authors'])) {
            $validated['popular_authors'] = array_map('trim', explode(',', $validated['popular_authors']));
        } else {
            $validated['popular_authors'] = null;
        }

        // Set default values for checkboxes
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : true;

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has books
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category that has books assigned to it.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}

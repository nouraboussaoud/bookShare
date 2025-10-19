<?php

namespace App\Http\Controllers;

use App\Models\ReadingGroup;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReadingGroupController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of reading groups with pagination
     */
    public function index()
    {
        $groups = ReadingGroup::with(['owner', 'members'])
            ->withCount('members')
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new reading group
     */
    public function create()
    {
        $this->authorize('create', ReadingGroup::class);
        return view('groups.create');
    }

    /**
     * Store a newly created reading group
     */
    public function store(Request $request)
    {
        $this->authorize('create', ReadingGroup::class);

        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:reading_groups,name',
            'description' => 'nullable|string|max:1000',
            'is_private'  => 'boolean',
        ]);

        $group = ReadingGroup::create([
            'owner_id'    => auth()->id(),
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'is_private'  => $data['is_private'] ?? false,
            'status'      => 'active',
        ]);

        // Add the owner as a member with owner role
        $group->memberships()->create([
            'user_id'   => auth()->id(),
            'role'      => 'owner',
            'status'    => 'approved',
            'joined_at' => now(),
        ]);

        return redirect()
            ->route('reading-groups.show', $group)
            ->with('status', 'Reading group created successfully! Start inviting members.');
    }

    /**
     * Display a specific reading group
     */
    public function show(ReadingGroup $readingGroup)
    {
        $this->authorize('view', $readingGroup);
        $readingGroup->load('owner');
        
        // Load only approved members with their details
        $members = $readingGroup->members()
            ->wherePivot('status', 'approved')
            ->get();
        
        return view('groups.show', compact('readingGroup', 'members'));
    }

    /**
     * Show the form for editing a reading group
     */
    public function edit(ReadingGroup $readingGroup)
    {
        $this->authorize('update', $readingGroup);
        return view('groups.edit', compact('readingGroup'));
    }

    /**
     * Update a reading group
     */
    public function update(Request $request, ReadingGroup $readingGroup)
    {
        $this->authorize('update', $readingGroup);

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255|unique:reading_groups,name,' . $readingGroup->id,
            'description' => 'nullable|string|max:1000',
            'is_private'  => 'sometimes|boolean',
        ]);

        $readingGroup->update($data);

        return redirect()
            ->route('reading-groups.show', $readingGroup)
            ->with('status', 'Group information updated successfully.');
    }

    /**
     * Delete a reading group
     */
    public function destroy(ReadingGroup $readingGroup)
    {
        $this->authorize('delete', $readingGroup);

        $groupName = $readingGroup->name;
        $readingGroup->delete();

        return redirect()
            ->route('reading-groups.index')
            ->with('status', "Reading group '$groupName' has been deleted.");
    }

    /**
     * Search reading groups
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $groups = ReadingGroup::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['owner', 'members'])
            ->withCount('members')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('groups.index', compact('groups'));
    }
}

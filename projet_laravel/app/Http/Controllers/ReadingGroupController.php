<?php

namespace App\Http\Controllers;

use App\Models\ReadingGroup;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReadingGroupController extends Controller
{
    use AuthorizesRequests;

    // Liste paginée
    public function index()
    {
        $groups = ReadingGroup::with(['owner', 'members'])
            ->withCount('members')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('groups.index', compact('groups'));
    }

    // Formulaire création
    public function create()
    {
        $this->authorize('create', ReadingGroup::class);
        return view('groups.create');
    }

    // Enregistrement
    public function store(Request $request)
    {
        $this->authorize('create', ReadingGroup::class);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_private'  => 'boolean',
        ]);

        $group = ReadingGroup::create([
            'owner_id' => auth()->id(),   // Changed from 'user_id' to 'owner_id'
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_private' => $data['is_private'] ?? false,
        ]);

        // Ajouter le propriétaire comme membre
        $group->memberships()->create([
            'user_id'   => auth()->id(),
            'role'      => 'owner',
            'status'    => 'approved',
            'joined_at' => now(),
        ]);

        return redirect()
            ->route('reading-groups.show', $group)
            ->with('status', 'Group created.');
    }

    // Détail
    public function show(ReadingGroup $readingGroup)
    {
        $this->authorize('view', $readingGroup);
        $readingGroup->load(['owner', 'members']);
        $members = $readingGroup->members;
        return view('groups.show', compact('readingGroup', 'members'));
    }

    // Formulaire édition
    public function edit(ReadingGroup $readingGroup)
    {
        $this->authorize('update', $readingGroup);
        return view('groups.edit', compact('readingGroup'));
    }

    // Mise à jour
    public function update(Request $request, ReadingGroup $readingGroup)
    {
        $this->authorize('update', $readingGroup);

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_private'  => 'sometimes|boolean',
        ]);

        $readingGroup->update($data);

        return redirect()
            ->route('reading-groups.show', $readingGroup)
            ->with('status', 'Group updated.');
    }

    // Suppression
    public function destroy(ReadingGroup $readingGroup)
    {
        $this->authorize('delete', $readingGroup);

        $readingGroup->delete();

        return redirect()
            ->route('reading-groups.index')
            ->with('status', 'Group deleted.');
    }
}
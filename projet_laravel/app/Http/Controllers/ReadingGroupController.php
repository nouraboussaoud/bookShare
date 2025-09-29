<?php

namespace App\Http\Controllers;

use App\Models\ReadingGroup;
use Illuminate\Http\Request;

class ReadingGroupController extends Controller
{
    public function index()
    {
        $groups = ReadingGroup::with('owner')->paginate(10);
        return response()->json($groups);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_private' => 'boolean',
        ]);

        $group = ReadingGroup::create([
            ...$validated,
            'owner_id' => auth()->id(),
        ]);

        // Ajoute l’owner en tant que membre
        $group->memberships()->create([
            'user_id' => auth()->id(),
            'role' => 'owner',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        return response()->json($group, 201);
    }

    public function show(ReadingGroup $readingGroup)
    {
        $readingGroup->load(['owner', 'members']);
        return response()->json($readingGroup);
    }
}

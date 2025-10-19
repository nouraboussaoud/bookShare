<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadingGroup;
use Illuminate\Http\Request;

class GroupManagementController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q = $request->input('q');
        $perPage = (int) $request->input('per_page', 20);

        $query = ReadingGroup::query();
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // include members count if relation exists
        $query = $query->withCount(['members']);

        $groups = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // KPIs
        $totalGroups = ReadingGroup::count();
        $totalMembers = ReadingGroup::withCount('members')->get()->sum('members_count');

        return view('admin.groupes.index', compact('groups', 'totalGroups', 'totalMembers', 'q', 'perPage'));
    }

    public function show(ReadingGroup $readingGroup)
    {
        return view('admin.groupes.show', ['group' => $readingGroup]);
    }

    public function edit(ReadingGroup $readingGroup)
    {
        return view('admin.groupes.edit', ['group' => $readingGroup]);
    }

    public function update(Request $request, ReadingGroup $readingGroup)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $readingGroup->update($data);

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe mis à jour');
    }

    public function destroy(ReadingGroup $readingGroup)
    {
        $readingGroup->delete();
        return redirect()->route('admin.groupes.index')->with('success', 'Groupe supprimé');
    }
}

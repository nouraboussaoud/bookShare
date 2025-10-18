<?php

namespace App\Http\Controllers;

use App\Models\GroupMembership;
use App\Models\ReadingGroup;
use Illuminate\Http\Request;

class GroupMembershipController extends Controller
{
    public function join(Request $request, ReadingGroup $readingGroup)
    {
        // Déjà membre ?
        $already = GroupMembership::where('user_id', auth()->id())
            ->where('reading_group_id', $readingGroup->id)
            ->exists();

        if ($already) {
            return $this->respond($request, [
                'status' => 'already_member'
            ], 'You are already a member.', $readingGroup);
        }

        // Création
        $membership = GroupMembership::create([
            'user_id'          => auth()->id(),
            'reading_group_id' => $readingGroup->id,
            'role'             => 'member',
            'status'           => 'approved',
            'joined_at'        => now(),
        ]);

        return $this->respond($request, $membership, 'Joined group.', $readingGroup);
    }

    public function leave(Request $request, ReadingGroup $readingGroup)
    {
        // Empêcher le propriétaire de partir (sauf suppression du groupe)
        if ($readingGroup->owner_id === auth()->id()) {  // Changed from 'user_id' to 'owner_id'
            return $this->respond(
                $request,
                ['error' => 'owner_cannot_leave'],
                'Owner cannot leave own group.',
                $readingGroup,
                true
            );
        }

        GroupMembership::where('user_id', auth()->id())
            ->where('reading_group_id', $readingGroup->id)
            ->delete();

        return $this->respond($request, ['status' => 'left'], 'Left group.', $readingGroup);
    }

    private function respond(Request $request, $data, string $flash, ReadingGroup $group, bool $error = false)
    {
        if ($request->wantsJson()) {
            return response()->json($data, $error ? 422 : 200);
        }

        return redirect()
            ->route('reading-groups.show', $group)
            ->with($error ? 'error' : 'status', $flash);
    }
}
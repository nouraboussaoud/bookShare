<?php

namespace App\Http\Controllers;

use App\Models\GroupMembership;
use App\Models\ReadingGroup;

class GroupMembershipController extends Controller
{
    public function join(ReadingGroup $readingGroup)
    {
        $membership = GroupMembership::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'reading_group_id' => $readingGroup->id,
            ],
            [
                'role' => 'member',
                'status' => 'approved',
                'joined_at' => now(),
            ]
        );

        return response()->json($membership);
    }

    public function leave(ReadingGroup $readingGroup)
    {
        GroupMembership::where('user_id', auth()->id())
            ->where('reading_group_id', $readingGroup->id)
            ->delete();

        return response()->json(['message' => 'Left group successfully']);
    }
}

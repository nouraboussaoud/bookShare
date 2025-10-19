<?php

namespace App\Http\Controllers;

use App\Models\GroupMembership;
use App\Models\ReadingGroup;
use Illuminate\Http\Request;

class GroupMembershipController extends Controller
{
    /**
     * Join a reading group
     */
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

        // Check if group has reached max members
        if ($readingGroup->max_members && $readingGroup->members()->count() >= $readingGroup->max_members) {
            return $this->respond(
                $request,
                ['error' => 'group_full'],
                'This group has reached its maximum member limit.',
                $readingGroup,
                true
            );
        }

        // For private groups, require approval
        $status = $readingGroup->is_private ? 'pending' : 'approved';

        // Création
        $membership = GroupMembership::create([
            'user_id'          => auth()->id(),
            'reading_group_id' => $readingGroup->id,
            'role'             => 'member',
            'status'           => $status,
            'joined_at'        => $status === 'approved' ? now() : null,
        ]);

        $message = $readingGroup->is_private 
            ? 'Your request to join has been sent to the group owner.'
            : 'Successfully joined the group!';

        return $this->respond($request, $membership, $message, $readingGroup);
    }

    /**
     * Leave a reading group
     */
    public function leave(Request $request, ReadingGroup $readingGroup)
    {
        // Empêcher le propriétaire de partir (sauf suppression du groupe)
        if ($readingGroup->owner_id === auth()->id()) {
            return $this->respond(
                $request,
                ['error' => 'owner_cannot_leave'],
                'Owner cannot leave own group. Delete the group instead.',
                $readingGroup,
                true
            );
        }

        GroupMembership::where('user_id', auth()->id())
            ->where('reading_group_id', $readingGroup->id)
            ->delete();

        return $this->respond($request, ['status' => 'left'], 'You have left the group.', $readingGroup);
    }

    /**
     * Approve a membership request (owner only)
     */
    public function approve(Request $request, ReadingGroup $readingGroup, GroupMembership $membership)
    {
        $this->authorize('manageMembership', $readingGroup);

        if ($membership->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        $membership->update([
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        $message = "Member request approved.";
        
        if ($request->wantsJson()) {
            return response()->json(['status' => 'approved'], 200);
        }

        return redirect()
            ->route('reading-groups.show', $readingGroup)
            ->with('status', $message);
    }

    /**
     * Reject a membership request (owner only)
     */
    public function reject(Request $request, ReadingGroup $readingGroup, GroupMembership $membership)
    {
        $this->authorize('manageMembership', $readingGroup);

        if ($membership->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        $membership->delete();

        $message = "Member request rejected.";

        if ($request->wantsJson()) {
            return response()->json(['status' => 'rejected'], 200);
        }

        return redirect()
            ->route('reading-groups.show', $readingGroup)
            ->with('status', $message);
    }

    /**
     * Remove a member from the group (owner only)
     */
    public function remove(Request $request, ReadingGroup $readingGroup, $userId)
    {
        $this->authorize('manageMembership', $readingGroup);

        $membership = GroupMembership::where('reading_group_id', $readingGroup->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($readingGroup->owner_id === $userId) {
            return $this->respond(
                $request,
                ['error' => 'cannot_remove_owner'],
                'Cannot remove the group owner.',
                $readingGroup,
                true
            );
        }

        $membership->delete();

        $message = "Member removed from group.";

        if ($request->wantsJson()) {
            return response()->json(['status' => 'removed'], 200);
        }

        return redirect()
            ->route('reading-groups.show', $readingGroup)
            ->with('status', $message);
    }

    /**
     * Change member role (owner only)
     */
    public function changeRole(Request $request, ReadingGroup $readingGroup, $userId)
    {
        $this->authorize('manageMembership', $readingGroup);

        $request->validate([
            'role' => 'required|in:member,moderator',
        ]);

        $membership = GroupMembership::where('reading_group_id', $readingGroup->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($readingGroup->owner_id === $userId) {
            return $this->respond(
                $request,
                ['error' => 'cannot_change_owner_role'],
                'Cannot change the role of the group owner.',
                $readingGroup,
                true
            );
        }

        $membership->update(['role' => $request->role]);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'role_updated', 'role' => $request->role], 200);
        }

        return redirect()
            ->route('reading-groups.show', $readingGroup)
            ->with('status', 'Member role updated.');
    }

    /**
     * Get members list with pending requests
     */
    public function getMembersList(ReadingGroup $readingGroup)
    {
        $this->authorize('view', $readingGroup);

        $members = $readingGroup->members()->get();
        $pendingRequests = GroupMembership::where('reading_group_id', $readingGroup->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return response()->json([
            'members' => $members,
            'pending_requests' => $pendingRequests,
        ]);
    }

    /**
     * Helper method for responding to requests
     */
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

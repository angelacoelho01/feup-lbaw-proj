<?php

namespace App\Policies;

use App\Models\UserGroup;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserGroup  $userGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserGroup $group)
    {
        $groupAdmins = $group->admins()->where('user_group_id', $group->id)->get();
        foreach ($groupAdmins as $groupAdmin) {
            if ($groupAdmin->id == $user->id || $user->is_admin) {
                return Response::allow();
            }
        }
        return Response::deny('You do not own this group');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserGroup  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserGroup $group)
    {
        $groupAdmins = $group->admins()->where('user_group_id', $group->id)->get();
        foreach ($groupAdmins as $groupAdmin) {
            if ($groupAdmin->id == $user->id || $user->is_admin) {
                return Response::allow();
            }
        }
        return Response::deny('You do not own this group');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewGroup(?User $user, UserGroup $group)
    {
        if ($group->is_public) {
            return true;
        }
        if ($user === null) {
            return false;
        }
        if ($user->is_admin) {
            return true;
        }
        $groupJoinRequests = $group->joinRequests()->where('accepted', 'true')->get();
        foreach ($groupJoinRequests as $joinRequest) {
            $member = User::where('id', $joinRequest->user_id)->where('id', $user->id)->get()->first();
            if ($member != null) {
                return true;
            }
        }
        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\Ban;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewProfile(?User $user, User $profile)
    {

        if ($user === null && !$profile->is_public) {
            return false;
        }

        if ($user !== null) {
            $isBanned = Ban::where('banned_id', $user->id)->get();

            if ($isBanned->first() !== null) {
                return false;
            }
        }

        if ($profile->is_public) {
            return true;
        }

        if ($user->is_admin) {
            return true;
        }

        if ($user->id === $profile->id) {
            return true;
        }

        $friends = FriendRequest::where('accepted', '=', true)
            ->where('sender_id', $profile->id)
            ->orWhere('receiver_id', $profile->id)->get();
        foreach ($friends as $friend) {
            if ($friend->sender()->get()->first()->id == $user->id) {
                return true;
            } elseif ($friend->receiver()->get()->first()->id == $user->id) {
                return true;
            }
        }

        return false;
    }

    public function changePassword(?User $user, User $profile)
    {
        return $user->id === $profile->id;
    }

    public function listGroups(User $user)
    {
        return Auth::check();
    }

    public function listFriends(User $user)
    {
        return Auth::check();
    }

    public function createPost(User $user)
    {
        return Auth::check();
    }
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(?User $user, User $profile)
    {
        if ($user === null) {
            return false;
        }

        $isBanned = Ban::where('banned_id', $user->id)->get();

        if ($isBanned->first() !== null) {
            return false;
        }

        return $user->is_admin ? true : $user->id === $profile->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $profile)
    {
        if ($user === null) {
            return false;
        }

        $isBanned = Ban::where('banned_id', $user->id)->get();

        if ($isBanned->first() !== null) {
            return false;
        }

        return $user->is_admin ? true : $user->id === $profile->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $profile)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $profile)
    {
        if ($user === null) {
            return false;
        }

        $isBanned = Ban::where('banned_id', $user->id)->get();

        if ($isBanned->first() !== null) {
            return false;
        }

        return $user->is_admin ? true : $user->id === $profile->id;
    }
}

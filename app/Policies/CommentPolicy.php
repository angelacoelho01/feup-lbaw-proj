<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use App\Models\FriendRequest;

class CommentPolicy
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
        return $user->is_admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Comment $comment)
    {
        if (User::find($comment->user_id)->is_public) {
            return Response::allow();
        }

        if ($user == null) {
            return Response::deny('This comment is private');
        }


        if ($comment->user_id == $user->id) {
            return Response::allow();
        }

        $friends = FriendRequest::where('accepted', '=', true)
        ->where('sender_id', $comment->user_id)
        ->orWhere('receiver_id', $comment->user_id)->get();
        foreach ($friends as $friend) {
            if ($friend->sender()->get()->first()->id == $user->id) {
                return Response::allow();
            } elseif ($friend->receiver()->get()->first()->id == $user->id) {
                return Response::allow();
            }
        }

        return Response::deny('This comment is private');

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user != null ?
            Response::allow() : Response::deny('You are not authenticated');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Comment $comment)
    {
        return ($comment->user_id == $user->id || $user->is_admin) ?
            Response::allow() : Response::deny('You do not own this comment');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Comment $comment)
    {
        if ($comment->user_id == $user->id) {
            return Response::allow();
        }

        if ($user->is_admin) {
            return Response::allow();
        }

        return Response::deny('You do not own this comment.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Comment $comment)
    {
        //
    }
}

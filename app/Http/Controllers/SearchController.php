<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\FriendRequest;
use App\Models\UserGroup;
use App\Models\JoinRequest;
use App\Models\Post;
use App\Models\Comment;

class SearchController extends Controller
{
    public function getFriends($user){

        $acceptedFriendRequests = array();
        
        $acceptedSentFriendRequests = $user->sentFriendRequests()->where('accepted', 'true')->get();
        foreach($acceptedSentFriendRequests as $acceptedSentFriendRequest){
            array_push($acceptedFriendRequests, $acceptedSentFriendRequest);
        }

        $acceptedReceivedFriendRequests = $user->receivedFriendRequests()->where('accepted', 'true')->get();
        foreach($acceptedReceivedFriendRequests as $acceptedReceivedFriendRequest){
            array_push($acceptedFriendRequests, $acceptedReceivedFriendRequest);
        }

        $friends = array();

        foreach($acceptedFriendRequests as $acceptedFriendRequest){

            $sender = $acceptedFriendRequest->sender()->get()->first();
            $receiver = $acceptedFriendRequest->receiver()->get()->first();

            if($sender->id != $user->id && !in_array($sender, $friends)){
                array_push($friends, $sender);
            } else if($receiver->id != $user->id && !in_array($receiver, $friends)){
                array_push($friends, $receiver);
            }
        }
        return $friends;
    }

    public function searchForProfiles($searchQuery){
        $profiles = array();
        $profiles = User::whereRaw(
            'tsvectors @@ plainto_tsquery(\'english\', ?)',
            [$searchQuery]
        )
            ->orderByRaw(
                'ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC',
                [$searchQuery]
            )->get();

        $user = Auth::user();
        $newProfileList = array();

        if ($user == null) {
            foreach($profiles as $profile){
                if($profile->is_public){
                    array_push($newProfileList, $profile);
                }
            }
        } else if (!$user->is_admin) {
    
            $friends = $this->getFriends($user);

            foreach($profiles as $profile){
                if($profile->is_public || in_array($profile, $friends)){
                    array_push($newProfileList, $profile);
                }
            }
        } else {
            $newProfileList = $profiles;
        }
        return $newProfileList;
    }

    public function searchForGroups($searchQuery){
        $groups = array();
        $groups = UserGroup::whereRaw(
            'tsvectors @@ plainto_tsquery(\'english\', ?)',
            [$searchQuery]
        )
            ->orderByRaw(
                'ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC',
                [$searchQuery]
            )->get();

        $user = Auth::user();
        $newGroupList = array();
        
        if ($user == null) {
            foreach($groups as $group){
                if ($group->is_public == 'true'){
                    array_push($newGroupList, $group);
                }
            }
        } else if (!$user->is_admin) {
            foreach($groups as $group){
                if ($group->is_public == 'true'){
                    array_push($newGroupList, $group);
                } else {
                    $groupJoinRequests = $group->joinRequests()->where('accepted', 'true')->get();
                    foreach($groupJoinRequests as $joinRequest){
                        if($joinRequest->user_id == $user->id) {
                            array_push($newGroupList, $group);
                        }
                    }
                    return $newGroupList;
                }
            }
        } else {
            $newGroupList = $groups;
        }
        return $newGroupList;
    }

    public function searchForPosts($searchQuery){
        $posts = Post::whereRaw(
            'tsvectors @@ plainto_tsquery(\'english\', ?)',
            [$searchQuery]
        )
            ->orderByRaw(
                'ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC',
                [$searchQuery])
            ->get();

        $user = Auth::user();
        $newPostList = array();

        if ($user == null) {
            foreach($posts as $post){
                if($post->is_public == 'true'){
                    array_push($newPostList, $post);
                }
            }
        } else if (!$user->is_admin) {
            $friends = $this->getFriends($user);

            foreach($posts as $post){
                if($post->is_public == 'true' || in_array($post->profile()->get()->first(), $friends)){
                    array_push($newPostList, $post);
                }
            }
        }
        $searchedProfiles = $this->searchForProfiles($searchQuery);

        foreach($searchedProfiles as $profile){

            $profilePosts = $profile->posts()->orderBy('date_time', 'DESC')->get();
            
            foreach($profilePosts as $profilePost){
                if(!in_array($profilePost, $newPostList)){
                    array_push($newPostList, $profilePost);  
                }
            }
        }
        return $newPostList;
    }

    public function searchForComments($searchQuery){

        $comments = Comment::whereRaw(
            'tsvectors @@ plainto_tsquery(\'english\', ?)',
            [$searchQuery]
        )
            ->orderByRaw(
                'ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC',
                [$searchQuery]
            )->get();

        $user = Auth::user();
        $newCommentList = array();

        if ($user == null) {
            foreach($comments as $comment){
                if($comment->post()->get()->first()->is_public == 'true' && $comment->profile()->get()->first()->is_public == 'true'){
                    array_push($newCommentList, $comment); 
                }
            }
        } else if (!$user->is_admin) {
            $friends = $this->getFriends($user);

            foreach($comments as $comment){

                $postProfile = $comment->post()->get()->first()->profile()->get()->first();
                $commentProfile = $comment->profile()->get()->first();

                if(($postProfile->is_public == 'true' || in_array($postProfile, $friends) || $postProfile->id == $user->id) && 
                ($commentProfile->is_public == 'true' || in_array($commentProfile, $friends) || $commentProfile->id == $user->id)){
                    array_push($newCommentList, $comment); 
                }
            }
        } else {
            $newCommentList = $comments;
        }
        
        return $newCommentList;

    }

    public function show(Request $request){
       
        $searchQuery = $request->input('search');
        $profiles = $this->searchForProfiles($searchQuery);
        $groups = $this->searchForGroups($searchQuery);
        $posts = $this->searchForPosts($searchQuery);
        $comments = $this->searchForComments($searchQuery);

        return view('pages.search', [
            'profiles' => $profiles,
            'groups' => $groups,
            'posts' => $posts,
            'comments' => $comments,
            'search_query' => $searchQuery
        ]);
    }

    public function filter(Request $request, $searchedQuery){

        $searchedProfiles = $this->searchForProfiles($searchedQuery);
        $searchedGroups = $this->searchForGroups($searchedQuery);
        $searchedPosts = $this->searchForPosts($searchedQuery);
        $searchedComments = $this->searchForComments($searchedQuery);

        $profilesToBeSend = [];
        $groupsToBeSend = [];
        $postsToBeSend = [];
        $commentsToBeSend = [];

        if($request->all == "on"){
            $profilesToBeSend = $searchedProfiles;
            $groupsToBeSend = $searchedGroups;
            $postsToBeSend = $searchedPosts;
            $commentsToBeSend = $searchedComments;
        } 
        if ($request->profiles == 'on'){
            $profilesToBeSend = $searchedProfiles;
        }
        if ($request->groups == 'on'){
            $groupsToBeSend = $searchedGroups;
        }
        if ($request->posts == 'on'){
            $postsToBeSend = $searchedPosts;
        }
        if ($request->comments == 'on'){
            $commentsToBeSend = $searchedComments;
        }

        return view('pages.search', [
            'profiles' => $profilesToBeSend,
            'groups' => $groupsToBeSend,
            'posts' => $postsToBeSend,
            'comments' => $commentsToBeSend,
            'search_query' => $searchedQuery
        ]);
    }
}

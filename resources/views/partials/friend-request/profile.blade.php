@if (Auth::user() != null && Auth::user()->id != $profile->id)
@php 
    $accepted = false;
    $sent = false;
    foreach (Auth::user()->receivedFriendRequests as $receivedFriendRequest) {
        if ($profile->id == $receivedFriendRequest->sender->id && $receivedFriendRequest->accepted == true){
            $accepted = true;
            $friendRequest = $receivedFriendRequest;
        }
    }   
    foreach (Auth::user()->sentFriendRequests as $sentFriendRequest) {
        if ($profile->id == $sentFriendRequest->receiver->id && $sentFriendRequest->accepted == true){
            $accepted = true;
            $friendRequest = $sentFriendRequest;
        }
        else if ($profile->id == $sentFriendRequest->receiver->id && $sentFriendRequest->accepted == false)
            $sent = true;
            $friendRequest = $sentFriendRequest;
    }
@endphp

@if ($accepted)
    <form method="POST" action="{{ route('friend-requests.remove', ['friend_request_id' => $friendRequest->id]) }}"> 
        @csrf
        <button type=submit class="material-icons bg-white border-0" name="type" value="remove">person_remove</button>
    </form>
@elseif ($sent)
    <form method="POST" action="{{ route('friend-requests.remove', ['friend_request_id' => $friendRequest->id]) }}"> 
        @csrf
        <button type=submit class="material-icons bg-white border-0" name="type" value="remove">person_add_disabled</button>
    </form>
@else
    <form method="POST" action="{{ route('friend-requests.add', ['user_id' => $profile->id]) }}"> 
        @csrf
        <button type=submit class="material-icons bg-white border-0" name="type" value="add">person_add</button>
    </form>
@endif
@endif 
@if (Auth::user() != null && Auth::user()->id != $profile->id)
@php
    $accepted = false;
    $sent = false;
    $received = false;
    foreach (Auth::user()->receivedFriendRequests as $receivedFriendRequest) {

        if ($profile->id == $receivedFriendRequest->sender_id && $receivedFriendRequest->accepted == true){
            $accepted = true;
            $friendRequest = $receivedFriendRequest;
        }
        else if ($profile->id == $receivedFriendRequest->sender_id && $receivedFriendRequest->accepted == false){
            $received = true;
            $friendRequest = $receivedFriendRequest;
        }
    }   
    foreach (Auth::user()->sentFriendRequests as $sentFriendRequest) {

        if ($profile->id == $sentFriendRequest->receiver_id && $sentFriendRequest->accepted == true){
            $accepted = true;
            $friendRequest = $sentFriendRequest;
        }
        else if ($profile->id == $sentFriendRequest->receiver_id && $sentFriendRequest->accepted == false){
            $sent = true;
            $friendRequest = $sentFriendRequest;
        }
    }
@endphp

@if ($accepted)
    <form method="POST" action="{{ route('friend-requests.remove', ['friend_request_id' => $friendRequest->id]) }}"> 
        @csrf
        <button type=submit class="material-icons-big bg-white border-0" name="type" value="remove">person_remove</button>
    </form>
@elseif ($sent)
    <form method="POST" action="{{ route('friend-requests.remove', ['friend_request_id' => $friendRequest->id]) }}"> 
        @csrf
        <button type=submit class="material-icons-big bg-white border-0" name="type" value="remove">person_add_disabled</button>
    </form>
@elseif ($received)
    <form method="POST" action="{{ route('friend-requests.reply', ['friend_request_id' => $friendRequest->id]) }}" class="d-flex flex-row">
        @csrf
        <button type=submit class="material-icons-big bg-white border-0" name="type" value="accept">check</button>
        <button type=submit class="material-icons-big bg-white border-0" name="type" value="decline">close</button>
    </form>
@else
    <form method="POST" action="{{ route('friend-requests.add', ['user_id' => $profile->id]) }}"> 
        @csrf
        <button type=submit class="material-icons-big bg-white border-0" name="type" value="add">person_add</button>
    </form>
@endif
@endif 

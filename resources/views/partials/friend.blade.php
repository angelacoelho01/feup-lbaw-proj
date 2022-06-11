@php
$sender = $friend->sender()->get()->first();
$receiver = $friend->receiver()->get()->first();

@endphp
@if($sender !== null && $receiver !== null)
<div class="d-flex justify-content-between">
    <div>
        @if(Auth::user()->id == $sender->id)
        <img class="rounded-circle" src="{{ Storage::url($receiver->profile_pic) }}" alt="Profile picture" width="60" height="60"/>
        @else
        <img class="rounded-circle" src="{{ Storage::url($sender->profile_pic) }}" alt="Profile picture" width="60" height="60"/>
        @endif
        <span class="ps-2">
            @if ($profile->id == $sender->id)
            <a href="{{ url('profile/' . $receiver->id) }}">
                {{ $receiver->name }}
            </a>
            @else
            <a href="{{ url('profile/' . $sender->id) }}">
                {{ $sender->name }}
            </a>
            @endif
        </span>
    </div>

    @if($friend->accepted)
    <div class="align-self-center">
        <form method="POST" action="{{ route('friend-requests.remove', ['friend_request_id' => $friend->id]) }}">
            @csrf
            <button type="submit" class="material-icons border-0 bg-0" name="type" value="remove">person_remove</button>
        </form>
    </div>
    @endif
</div>
@endif

@php
$sender = $friendRequest->sender()->get()->first();
$receiver = $friendRequest->receiver()->get()->first();

@endphp
@if(Auth::user()->id == $receiver->id)
    <div class="p-1">
        <img class="rounded-circle" src="{{ Storage::url($sender->profile_pic) }}" alt="Profile picture" width="50" height="50" />
        <span class="ps-2">
            <a href="{{ url('profile/' . $sender->id) }}">
                {{ $sender->name }}
            </a>
        </span>
    </div>

    @if(!$friendRequest->accepted)
    <div class="align-self-center">
        <form method="POST" action="{{ route('friend-requests.reply', ['friend_request_id' => $friendRequest->id]) }}" class="d-flex flex-row">
            @csrf
            <button type="submit" class="material-icons border-0 bg-0" name="type" value="accept">check</button>
            <button type="submit" class="material-icons border-0 bg-0" name="type" value="decline">close</button>
        </form>
    </div>
    @endif
@endif

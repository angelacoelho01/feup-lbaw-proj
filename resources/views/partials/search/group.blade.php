@php
    $sent = false;
    $accepeted = false;
    if(Auth::user() != null){
        $joinRequests = $group->joinRequests()->get();
        foreach($joinRequests as $joinRequest){
            if($joinRequest->user_id == Auth::user()->id){
                $sent = true; 
                $accepted = $joinRequest->accepted == 'true';
                break;
            } 
        }
    }
@endphp

<div class="container d-flex flex-column rounded-3 shadow-sm bg-white p-4">

    <div class="d-flex flex-row justify-content-between align-items-center">

        <div class="d-flex flex-row gap-3">
            <div>
            @if($group !== null)
                <a href="{{ url('/group/' . $group->id) }}">
                    <img class="rounded-circle" src="{{ Storage::url($group->group_pic) }}" width="60" height="60">
                </a>
            </div>
            <div class="d-flex flex-column justify-content-center">
                <a href="{{ url('/group/' . $group->id) }}">
                    <div> {{ $group->name }} </div>
                </a>
                @if ($group->description != null)
                <div class="text-secondary fs-6 fw-light">{{ $group->description }}</div>
                @endif
            @endif
            </div>
        </div>
        <div>
            @if(Auth::user()!= null)
                @if ($sent && $accepted)
                    <form method="POST" id="" action="{{route('group.leave', $group->id)}}">
                       @method('DELETE')
                        {{ csrf_field() }}
                        <span class="material-icons-big">
                            <input value="group_remove" type="submit" name="submit" class="no-button" />
                        </span>
                    </form>
                @elseif (!$sent)
                    <form method="POST" id="" action="{{route('group.join', $group->id)}}">
                        {{ csrf_field() }}
                        <span class="material-icons-big">
                            <input value="group_add" type="submit" name="submit" class="no-button" />
                        </span>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>


<div class="container d-flex flex-column rounded-3 shadow-sm bg-white p-4">

    <div class="d-flex flex-row justify-content-between align-items-center">

        <div class="d-flex flex-row gap-3">
            <div>
                @php
                $banned = $ban->banned()->get()->first()
                @endphp
                @if($banned !== null)
                <a href="{{ url('/profile/' . $banned->id) }}">
                    <img class="rounded-circle" src="{{ Storage::url($banned->profile_pic) }}" width="60" height="60">
                </a>
                @else
                <img class="rounded-circle" src="{{ Storage::url('images/default.jpg') }}" width="60" height="60">
                @endif
            </div>
            <div class="d-flex flex-column justify-content-center">
                @if($banned!== null)
                <a href="{{ url('/profile/' . $banned->id) }}">
                    <div> {{ $banned->name }} </div>
                </a>
                @if ($ban->motive!= null)
                <div class="text-secondary fs-6 fw-light">Motive: {{ $ban->motive}}</div>
                @endif
                @else
                <h5> [deleted] </h5>
                @endif
            </div>
        </div>
        <div class="d-flex">
            <form method="POST" id="admin_tools" action="/admin/{{Auth::user()->id}}">
                {{ csrf_field() }}
                @method('PATCH')
                <input type="text" name="id" hidden value="{{$banned->id}}" />
                <span class="material-icons-big">
                    <input value="lock_open" type="submit" name="submit" class="no-button" />
                </span>
                <span class="material-icons-big">
                    <input value="delete" type="submit" name="submit" class="no-button" />
                </span>
            </form>
        </div>
    </div>
</div>
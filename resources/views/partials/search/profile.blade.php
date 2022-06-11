<div class="container d-flex flex-column rounded-3 shadow-sm bg-white p-4">

    <div class="d-flex flex-row justify-content-between align-items-center">

        <div class="d-flex flex-row gap-3">
            <div>
                @if($profile !== null)
                <a href="{{ url('/profile/' . $profile->id) }}">
                    <img class="rounded-circle" src="{{ Storage::url($profile->profile_pic) }}" width="60" height="60">
                </a>
                @else
                <img class="rounded-circle" src="{{ Storage::url('images/default.jpg') }}" width="60" height="60">
                @endif
            </div>
            <div class="d-flex flex-column justify-content-center">
                @if($profile !== null)
                <a href="{{ url('/profile/' . $profile->id) }}">
                    <div> {{ $profile->name }} </div>
                </a>
                @if ($profile->description != null)
                <div class="text-secondary fs-6 fw-light">{{ $profile->description }}</div>
                @endif
                @else
                <h5> [deleted] </h5>
                @endif
            </div>
        </div>
        <div class="d-flex">
            @if(Auth::user()!= null && Auth::user()->is_admin && Auth::user()->id != $profile->id)
            @php
            $bannedByUser = App\Models\Ban::where('banned_id', $profile->id)->where('user_id', Auth::user()->id)->get()->first();
            $isBanned = App\Models\Ban::where('banned_id', $profile->id)->get()->first();
            @endphp
            @if($bannedByUser)
            <a href="/admin/{{Auth::user()->id}}">
                <span class="material-icons-big">lock_open</span>
            </a>
            @endif
            @if($isBanned === null)
            <a href="#" data-bs-toggle="modal" data-bs-target="#submit-ban-modal-{{ $profile->id }}">
                <span class="material-icons-big">block</span>
            </a>
            @endif
            <a href="#" data-bs-toggle="modal" data-bs-target="#delete-modal-{{ $profile->id }}">
                <span class="material-icons-big">delete</span>
            </a>
            @endif
            @include('partials.friend-request.friend-request', $profile)
        </div>
    </div>
</div>
        
@if(Auth::user()!= null && Auth::user()->is_admin)
<div class=" modal fade" id="submit-ban-modal-{{ $profile->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="submit-ban-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submit-ban-modal-label">Submit Ban</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="admin_tools" action="/admin/{{Auth::user()->id}}" class="d-flex flex-column gap-3 align-items-end" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <input type="text" name="id" hidden value="{{$profile->id}}" />
                    <div class="form-group">
                        <textarea name='ban-motive' class="form-control border-0" id="ban-motive-field" placeholder="Reason for banning" cols="100" rows="5" style="resize: none;" required></textarea>
                    </div>
                    <div class="container d-flex flex-row justify-content-between p-0">
                        <div class="form-group">
                            <input class="btn btn-primary form-control" type="submit" value="Submit Ban">
                        </div>
                    </div>
                    <input type="hidden" value="{{ Session::token() }}" name="_token">
                </form>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class=" modal fade" id="delete-modal-{{ $profile->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-modal-label">Delete this User?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="admin_tools" action="/admin/{{Auth::user()->id}}" class="d-flex flex-column gap-3 align-items-center" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <input type="text" name="id" hidden value="{{$profile->id}}" />
                    <input type="text" name="delete" hidden value="delete" />
                    Are you sure?
                    <input class="btn btn-primary form-control" type="submit" value="Yes">
                    <input type="hidden" value="{{ Session::token() }}" name="_token">
                </form>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
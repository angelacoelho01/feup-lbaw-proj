@extends('layouts.app')

@section('header')
@if (Auth::check())
@include('partials.headers.authenticated_user')
@else
@include('partials.headers.visitor')
@endif
@endsection



@section('content')

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<div class="container-fluid d-flex flex-row row justify-content-center">

    <div class="d-flex flex-column col-3 justify-content-start align-items-end">

        <div class="d-flex flex-column justify-content-end align-items-stretch gap-4 w-100" style="top: 5rem;">
            <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">

                <div class="d-flex flex-row justify-content-end">
                    @can('update', $user)
                    <a href="/profile/{{ $user->id }}/edit">
                        <span class="material-icons">edit</span>
                    </a>

                    @endcan
                    @if(Auth::user() !== null && Auth::user()->id === $user->id && $user->is_admin)
                    <a href="/admin/{{ $user->id }}">
                        <span class="material-icons">admin_panel_settings</span>
                    </a>
                    @endif
                </div>

                <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                    <img class="rounded-circle img-thumbnail" src="{{ Storage::url($user->profile_pic) }}" alt="Profile Picture" width="100" />
                    <div class="d-flex flex-column justify-content-start align-items-center">
                        <div class="fs-3 text-wrap text-center">{{ $user->name }}</div>
                        @if ($user->nickname !== null)
                        <div>({{ $user->nickname }})</div>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column justify-content-center align-items-start gap-2 fw-light">

                    @if ($user->description !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            description
                        </div>
                        <div>{{ $user->description }}</div>
                    </div>
                    @endif

                    @if ($user->website !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            link
                        </div>
                        <a href="{{ $user->website }}">{{ $user->website }}</a>
                    </div>
                    @endif

                    @if ($user->birthdate !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            cake
                        </div>
                        <div>{{ $user->birthdate }}</div>
                    </div>
                    @endif

                    @if ($user->phone_number !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            phone
                        </div>
                        <div>{{ $user->phone_number }}</div>
                    </div>

                    @endif
                    @if ($user->gender !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            person
                        </div>
                        <div>{{ $user->gender }}</div>
                    </div>

                    @endif
                    @if ($user->political_ideology !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            campaign
                        </div>
                        <div>{{ $user->political_ideology }}</div>
                    </div>

                    @endif
                    @if ($user->religious_belief !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            self_improvement
                        </div>
                        <div>{{ $user->religious_belief }}</div>
                    </div>

                    @endif
                </div>

                <div class="d-flex flex-column justify-content-start align-items-start gap-1 fw-light">
                    @if ($hometown !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            gite
                        </div>
                        <div>{{ $hometown->name }}</div>
                    </div>

                    @endif
                    @if ($currentCity !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            location_city
                        </div>
                        <div>
                            {{ $currentCity->name }}
                        </div>
                    </div>
                    @endif
                    @if ($education !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            school
                        </div>
                        <div>
                            {{ $education->place()->get()->first()->name }}
                        </div>
                    </div>
                    @endif
                    @if ($employment !== null)
                    <div class="d-flex flex-row align-items-center gap-2 lh-1">
                        <div class="material-icons">
                            work
                        </div>
                        <div>
                            {{ $employment->place()->get()->first()->name }}
                        </div>
                    </div>

                    @endif
                </div>
            </div>

            <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">
                <div class="d-flex flex-column gap-3 p-3">
                    <div class="d-flex justify-content-between">
                        <h3>Friends</h3>
                        @if (count($friends) > 0)
                        <a href="/profile/{{ $user->id }}/friends">See all...</a>
                        @endif
                    </div>
                    @can('listFriends', $user)
                    @if($friends->isEmpty())
                    <p>No friends here yet!</p>
                    @else
                    @foreach($friends as $friend)
                        @include('partials.friend', ['profile' => $user])
                    @endforeach
                    @endif
                    @else
                    <p>Nothing to show here</p>
                    @endcan
                </div>
            </div>
            <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">
                <div class="d-flex flex-column gap-2 p-2">
                    <div class="d-flex justify-content-between">
                        <h3>Groups</h3>
                        @if (count($groups) > 0)
                        <a href="/profile/{{ $user->id }}/groups">See all...</a>
                        @endif
                    </div>
                    @can('listGroups', $user)
                    @if ($groups->isEmpty())
                    <p>No groups here yet!</p>
                    @else
                    @foreach($groups as $group)
                    @include('partials.groups.group', ['profile' => $user])
                    @endforeach
                    @endif
                    <div><a class="btn shadow-sm btn-primary" href="{{route('create.page')}}">Create Group</a></div>
                    @else
                    <p>Nothing to show here</p>
                    @endif
                </div>
            </div>
        </div>

    </div>


    <div class="d-flex flex-column col-5 justify-content-start my-2">
        <div class="d-flex flex-column gap-3 justify-self-center">
            @if (Auth::user() != null)
            @can('createPost', Auth::user())
            @if (Auth::user()->id === $user->id)
            @include('partials.posts.create', ['group' => null])
            @endif
            @endcan
            @endif
            <div id="post-data" class="d-flex flex-column gap-3">
                @each('partials.posts.post', $posts, 'post')
            </div>
        </div>
    </div>





</div>

<script type="text/javascript">
    var page = 1;

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            loadMoreData(page);
        }
    });

    function loadMoreData(page) {
        $.ajax({
                url: '?page=' + page,
                type: "get",
                beforeSend: function() {
                    $('.ajax-load').show();

                }
            })
            .done(function(data) {
                if (data.html == " ") {
                    $('.ajax-load').html("No more records found");
                    return;
                }

                $('.ajax-load').hide();
                $("#post-data").append(data.html);
            })

            .fail(function(jqXHR, ajaxOptions, thrownError) {
                alert('server not responding...');
            });
    }
</script>

@endsection

@section('footer')
@include('partials.footer')
@endsection

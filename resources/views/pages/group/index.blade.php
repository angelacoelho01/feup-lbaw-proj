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

        <div class="d-flex flex-column col-3 justify-content-start align-items-stretch ">

            <div class="d-flex flex-column justify-content-end align-items-stretch gap-4 w-100"
                style="top: 5rem;">
                <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">

                    <div class="d-flex flex-row justify-content-end">
                        @can('update', $group)
                            <a href="/group/{{ $group->id }}/edit">
                                <span class="material-icons">edit</span>
                            </a>
                        @endcan
                        @php
                            $sent = $group->joinRequests()->where('user_id', Auth::user()->id)->get()->first();
                        @endphp
                        @if($sent === null)
                            <form method="POST" id="" action="{{route('group.join', $group->id)}}">
                                {{ csrf_field() }}
                                <span class="material-icons">
                                    <input value="group_add" type="submit" name="submit" class="no-button" />
                                </span>
                            </form>
                        @elseif($sent->accepted)
                            <form method="POST" id="" action="{{route('group.leave', $group->id)}}">
                                @method('DELETE')
                                {{ csrf_field() }}
                                <span class="material-icons">
                                    <input value="group_remove" type="submit" name="submit" class="no-button" />
                                </span>
                            </form>
                        @endif
                    </div>

                    <div class="d-flex flex-column justify-content-center align-items-center gap-3">
                        <img class="rounded-circle img-thumbnail" src="{{ Storage::url($group->group_pic) }}"
                            alt="Profile Picture" width="300" />
                        <div class="d-flex flex-column justify-content-start align-items-center">
                            <div class="fs-3 text-wrap text-center">{{ $group->name }}</div>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 fw-light">
                        @if ($group->description !== null)
                            <div class="d-flex flex-row gap-2 lh-1">
                                <div>{{ $group->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">
                    <div class="d-flex flex-column gap-3 p-3">
                        <div class="d-flex flex-row justify-content-between">
                            <h3>Members</h3>
                            <a class="mt-1" href="{{route('group.members', $group->id)}}">See all...</a>
                        </div>
                        <div class="d-flex flex-column">
                            @if (count($members) == 0)
                                <p>No members here yet!</p>
                            @else
                                @foreach ($members as $member)
                                    @include('partials.groups.member', $member)
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @can('update', $group)
                    <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">
                        <div class="d-flex flex-column gap-3 p-3">
                            <div class="d-flex flex-row justify-content-between">
                                <h3>Join Requests</h3>
                            </div>
                            <div class="d-flex flex-column">
                                @if (count($joinRequests)==0)
                                    <p>No join requests to answer!</p>
                                @else
                                    @each('partials.groups.join-request', $joinRequests, 'joinRequest')
                                @endif
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>


        <div class="d-flex flex-column col-5 justify-content-start my-2">
            <div class="d-flex flex-column gap-3 justify-self-center">
                @if (Auth::user() != null)
                    @can('createPost', Auth::user())
                        @include('partials.posts.create', ['group' => $group])
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

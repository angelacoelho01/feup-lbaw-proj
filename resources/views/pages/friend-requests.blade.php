@extends('layouts.app')

@section('header')
    @if (Auth::check())
        @include('partials.headers.authenticated_user')
    @else
        @include('partials.headers.visitor')
    @endif
@endsection

@section('content')

    <div class="container-fluid d-flex flex-row justify-content-between row">

        @if (Auth::check())
            @include('partials.sidebars.authenticated_user')
        @else
            @include('partials.sidebar.visitor')
        @endif

        <div class="d-flex col-8 justify-content-start">
            <div class="d-flex flex-column gap-3 justify-self-center w-50 ms-5">
                @if (count($friend_requests) > 0)
                    @each('partials.friend-request', $friend_requests, 'friend_request')
                @else
                    <span class="align-self-center text-muted">No users found!</span>
                @endif
            </div>
        </div>

    </div>

@endsection

@section('footer')
    @include('partials.footer')
@endsection

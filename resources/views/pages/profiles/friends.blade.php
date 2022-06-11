@extends('layouts.app')

@section('header')
@if (Auth::check())
@include('partials.headers.authenticated_user')
@else
@include('partials.headers.visitor')
@endif
@endsection

@section('content')

<div class="container-fluid d-flex flex-column justify-content-center align-items-center">   
    <div class="d-flex flex-column align-items-center gap-3 p-3 w-75">
        <div class="d-flex flex-column col-4">
            <div class="d-flex flex-row justify-content-center p-3 gap-3">
                <h3>Your Friend Requests</h3>
            </div>
            <div class="d-flex flex-row justify-content-between">
            @can('listFriends', Auth::user())
            @if (count($friendRequests) > 0)
                @foreach($friendRequests as $friendRequest)
                    @include('partials.friendRequest')                            
                @endforeach
            @else
                <p>Ups! It seems you have no Friend Requests at the moment</p>
            @endif
            @endcan
            </div>
        </div>
    </div>
    <div class="d-flex flex-column align-items-center gap-3 p-3 w-75">
        <h3>All Friends</h3>
        @foreach($friends as $friend)
        <div class="col-4">
            @include('partials.friend')
        </div>
        @endforeach
    </div>
</div>

@endsection

@section('footer')
@include('partials.footer')
@endsection
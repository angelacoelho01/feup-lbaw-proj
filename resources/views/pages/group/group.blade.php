@extends('layout.app')

@section('header')
    @if (Auth::check())
        @include('partials.headers.authenticated_user')
    @else
        @include('partials.headers.visitor')
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h4><b>Your Groups</b></h4>
            <hr>
            @each('partials.groups.group', Auth::usergroups, 'group')
        </div>
    </div>
@endsection

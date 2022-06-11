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

        <div class="d-flex col-2 flex-column justify-content-start align-items-start">
        @if (Auth::check())
            @include('partials.sidebars.authenticated_user')
        @else
            @include('partials.sidebars.visitor')
        @endif
    </div>
        <div class="container d-flex p-2 flex-column gap-3" style="max-width: 40%">
            @include('partials.posts.post', $post)
        </div>


        <div class="d-flex col-2 justify-content-start">
        </div>
    </div>

@endsection

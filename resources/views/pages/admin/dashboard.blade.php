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

    <div class="d-flex col justify-content-start">
        <nav class="navbar d-flex flex-column justify-content-start align-items-start p-2 gap-4">
            @if (Auth::check())
            <a class="nav-item" href="#">
                <div class="d-flex flex-row align-items-center gap-3">
                    <span class="material-icons">people</span>
                    <span> Users </span>
                </div>
            </a>
            <a class="nav-item" href="#">
                <div class="d-flex flex-row align-items-center gap-3">
                    <span class="material-icons">groups</span>
                    <span> Groups </span>
                </div>
            </a>
            @endif
            <a class="nav-item" href="#">
                <div class="d-flex flex-row align-items-center gap-3">
                    <span class="material-icons">feed</span>
                    <span> Posts </span>
                </div>
            </a>
            <a class="nav-item" href="#">
                <div class="d-flex flex-row align-items-center gap-3">
                    <span class="material-icons">comment</span>
                    <span> Comments </span>
                </div>
            </a>
        </nav>
    </div>

    <div class="d-flex col-8 justify-content-start">
        <div class="d-flex flex-column gap-3 justify-self-center w-50 ms-5">
            <h1>Your Bans</h1>
            @each('partials.admin.ban', $bans, 'ban')
        </div>
    </div>

</div>

@endsection

@section('footer')
@include('partials.footer')
@endsection
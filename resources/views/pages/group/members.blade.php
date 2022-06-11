@extends('layouts.app')

@section('header')
@if (Auth::check())
@include('partials.headers.authenticated_user')
@else
@include('partials.headers.visitor')
@endif
@endsection

@section('content')

<div class="container-fluid d-flex flex-row justify-content-center row">
    <div class="d-flex col-8 justify-content-center">
        <div class="d-flex flex-column gap-3 justify-self-center w-50 ms-5">
            <h1 class="py-4">All Members</h1>
            @each('partials.groups.member', $members, 'member')
        </div>
    </div>

</div>

@endsection

@section('footer')
@include('partials.footer')
@endsection

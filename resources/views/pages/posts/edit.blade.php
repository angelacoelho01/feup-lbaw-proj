@extends('layouts.app')

@section('header')
    @if (Auth::check())
        @include('partials.headers.authenticated_user')
    @else
        @include('partials.headers.visitor')
    @endif
@endsection

@section('content')
    <div class="container d-flex flex-column align-items-center gap-3 rounded shadow-sm bg-white p-3 w-50">
        @include('partials.posts.edit', $post)
    </div>
@endsection

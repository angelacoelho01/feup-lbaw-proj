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
    <form action="{{route('search.filter', $search_query)}}" method="GET" class="d-flex col-2">
        @include('partials.sidebars.search')
    </form>
    <div class="d-flex col-8 justify-content-start">
        <div class="d-flex flex-column gap-3 justify-self-center w-50">
            @if(!empty($profiles) || !empty($posts) || !empty($groups)|| !empty($comments) )
                <p class="align-self-center">
                    <span class="fs-4">Search results for </span>
                    <span class="fs-4 fw-bold">{{ $search_query }}</span>
                </p>
            @else
                <p class="align-self-center mt-4">
                    <span class="fs-4">No results for </span>
                    <span class="fs-4 fw-bold">{{ $search_query }}</span>
                </p>
            @endif
            @if (!empty($profiles))
                @each('partials.search.profile', $profiles, 'profile')
            @endif
            @if (!empty($groups))
                @each('partials.search.group', $groups, 'group')
            @endif
            @if (!empty($comments))
                @each('partials.search.comment', $comments, 'comment')
            @endif
            @if (!empty($posts))
                <div id="post-data" class="d-flex flex-column gap-3">
                    @each('partials.posts.post', $posts, 'post')
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('footer')
@include('partials.footer')
@endsection

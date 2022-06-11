@extends('layouts.app') 

@section('header')
    @if (Auth::check())
        @include('partials.headers.authenticated_user')
    @else
        @include('partials.headers.visitor')
    @endif
@endsection

@section('content')
<div class="container-fluid d-flex flex-row justify-content-center align-items-center w-50 my-5">
    <div class="d-flex flex-column gap-5 shadow-sm bg-white p-3 h-50 w-50 p-4">
        <form method="POST" action="{{ route('group.create') }}">
            {{ csrf_field() }}

            <h1>Create group</h1>
            <input
                id="group-name"
                class="form-control mb-3 mt-3"
                type="text"
                placeholder="Group Name"
                name="group-name"
                value="{{ old('group-name') }}"
                required
                autofocus
            />
            Choose group visibility:
            <div class="form-check mb-3 mt-2">
                <input type="radio" id="radio-button-1" class="form-check-input" name="is-public" value="true">
                <label class="form-check-label" for="radio-button-1">Public</label>
            </div>
            <div class="form-check mb-3">
                <input type="radio" id="radio-button-2" class="form-check-input" name="is-public" value="false">
                <label class="form-check-label" for="radio-button-2">Private</label>
            </div>
            <button class="btn btn-primary mb-3" type="submit">Create</button>
            <br />
        </form>
    </div>
</div>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
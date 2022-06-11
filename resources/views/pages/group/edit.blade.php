@extends('layouts.app')

@section('header')
    @if (Auth::check())
        @include('partials.headers.authenticated_user')
    @else
        @include('partials.headers.visitor')
    @endif
@endsection

@section('content')
    <div class="container rounded shadow-sm bg-white d-flex flex-column gap-5 p-5">
        <form method="POST" enctype="multipart/form-data" action="/group/{{ $group->id }}">
            {{ csrf_field() }}
            @method('PATCH')

            <h1>Group settings</h1>

            <label for="image">Group Image</label>
            <input type="file" class="form-control " name="image" id="image" />

            <input id="name" class="form-control my-3" type="text" placeholder="name" name="name"
                value="{{ old('name') ?? $group->name }}" />
            @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
            @endif

            <textarea name="description" id="description" class="form-control my-3" rows="5"
                placeholder="Description">{{ old('description') ?? $group->description }}</textarea>

            <div class="form-check my-3">
                <input type="checkbox" name="is_public" id="is_public" class="form-check-input" value="true"
                    {{ old('is_public') ?? $group->is_public ? 'checked' : '' }} />
                <label class="form-check-label" for="is_public">
                    Public Group
                </label>
            </div>

            <button class="btn btn-primary mb-2" type="submit">Save Group Settings</button>


        </form>
        <form method="POST" class="d-flex flex-column justify-content-start align-items-start gap-3"
            action="/group/{{ $group->id }}">
            {{ csrf_field() }}
            @method('DELETE')
            <h1 class="">Danger Zone</h1>

            <div class="d-flex flex-column justify-content-start align-items-start">
                <button id="delete-button" class="btn btn-danger mb-2" type="submit">Delete Group</button>
                <label for="delete-button fw-light">All your posts, comments, reactions and more will be made anonymous and
                    you will lose access to your account.</label>
            </div>
        </form>
        </section>
    @endsection

    @section('footer')
        @include('partials.footer')
    @endsection

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
    <form method="POST" enctype="multipart/form-data" action="/profile/{{ $user->id }}">
        {{ csrf_field() }}
        @method('PATCH')

        <h1>Edit your Profile</h1>

        <label for="image">Profile Image</label>
        <input type="file" class="form-control " name="image" id="image" />

        <input id="name" class="form-control my-3" type="text" placeholder="Your name" name="name" value="{{ old('name') ?? $user->name}}" />
        @if ($errors->has('name'))
        <span class="error">
            {{ $errors->first('name') }}
        </span>
        @endif

        <input id="nickname" class="form-control my-3" type="text" placeholder="Nickname" name="nickname" value="{{ old('nickname') ?? $user->nickname }}" />
        @if ($errors->has('nickname'))
        <span class="error">
            {{ $errors->first('nickname') }}
        </span>
        @endif

        <textarea name="description" id="description" class="form-control my-3" rows="5" placeholder="Description">{{ old('description') ?? $user->description }}</textarea>

        <input id="phone_number" class="form-control my-3" type="text" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number') ?? $user->phone_number }}" />
        @if ($errors->has('phone_number'))
        <span class="error">
            {{ $errors->first('phone_number') }}
        </span>
        @endif

        <input type="text" id="hometown" class="form-control my-3" name="hometown" placeholder="Hometown" value="{{ old('hometown') ?? ($hometown === null ? '' : $hometown->name) }}" />
        @if ($errors->has('hometown'))
        <span class="error">
            {{ $errors->first('hometown') }}
        </span>
        @endif

        <input type="text" id="current_city" class="form-control my-3" name="current_city" placeholder="Current City" value="{{ old('current_city') ?? ($currentCity === null ? '' : $currentCity->name) }}" />
        @if ($errors->has('current_city'))
        <span class="error">
            {{ $errors->first('current_city') }}
        </span>
        @endif
        <input type="text" id="education" class="form-control my-3" name="education" placeholder="Studied At" value="{{ old('education') ??
                    ($education === null
                        ? ''
                        : $education->place()->get()->first()->name) }}" />
        @if ($errors->has('education'))
        <span class="error">
            {{ $errors->first('education') }}
        </span>
        @endif
        <input type="text" id="employment" class="form-control my-3" name="employment" placeholder="Working at" value="{{ old('employment') ??
                    ($employment === null
                        ? ''
                        : $employment->place()->get()->first()->name) }}" />
        @if ($errors->has('employment'))
        <span class="error">
            {{ $errors->first('employment') }}
        </span>
        @endif

        <input id="website" class="form-control my-3" type="text" name="website" placeholder="Website" value="{{ old('website') ?? $user->website }}" />
        @if ($errors->has('website'))
        <span class="error">
            {{ $errors->first('website') }}
        </span>
        @endif

        <input id="gender" class="form-control my-3" type="text" placeholder="Gender" name="gender" value="{{ old('gender') ?? $user->gender }}" />
        @if ($errors->has('gender'))
        <span class="error">
            {{ $errors->first('gender') }}
        </span>
        @endif

        <div class="form-floating">
            <input id="birthdate" class="form-control my-3" type="date" placeholder="Birth date" name="birthdate" placeholder="Birthdate" value="{{ old('birthdate') ?? $user->birthdate }}" />
            <label for="birthdate">Birthdate</label>
        </div>
        @if ($errors->has('birthdate'))
        <span class="error">
            {{ $errors->first('birthdate') }}
        </span>
        @endif

        <div class="form-floating">
            <select id="political_ideology" name="political_ideology" class="form-select my-3">
                <option value="none" {{ $user->political_ideology == 'none' ? 'selected' : '' }}>None</option>
                <option value="Anarchism" {{ $user->political_ideology == 'Anarchism' ? 'selected' : '' }}>Anarchism
                </option>
                <option value="Liberalism" {{ $user->political_ideology == 'Liberalism' ? 'selected' : '' }}>
                    Liberalism</option>
                <option value="Conservatism" {{ $user->political_ideology == 'Conservatism' ? 'selected' : '' }}>
                    Conservatism</option>
                <option value="Socialism" {{ $user->political_ideology == 'Socialism' ? 'selected' : '' }}>Socialism
                </option>
                <option value="Communism" {{ $user->political_ideology == 'Communism' ? 'selected' : '' }}>Communism
                </option>
            </select>
            <label for="political_ideology">Political Ideology</label>
        </div>
        @if ($errors->has('political_ideology'))
        <span class="error">
            {{ $errors->first('political_ideology') }}
        </span>
        @endif

        <div class="form-floating">
            <select id="religious_belief" name="religious_belief" class="form-select my-3">
                <option value="none" {{ $user->religious_belief == 'none' ? 'selected' : '' }}>None</option>
                <option value="Christianity" {{ $user->religious_belief == 'Christianity' ? 'selected' : '' }}>
                    Christianity</option>
                <option value="Islamism" {{ $user->religious_belief == 'Islamism' ? 'selected' : '' }}>Islamism
                </option>
                <option value="Hinduism" {{ $user->religious_belief == 'Hinduism' ? 'selected' : '' }}>Hinduism
                </option>
                <option value="Buddism" {{ $user->religious_belief == 'Buddism' ? 'selected' : '' }}>Buddism</option>
                <option value="Judaism" {{ $user->religious_belief == 'Judaism' ? 'selected' : '' }}>Judaism</option>
            </select>
            <label for="religous_belief">Religious Belief</label>
        </div>
        @if ($errors->has('religous_belief'))
        <span class="error">
            {{ $errors->first('religous_belief') }}
        </span>
        @endif

        <div class="form-check my-3">
            <input type="checkbox" name="is_public" id="is_public" class="form-check-input" value="true" {{ old('is_public') ?? $user->is_public ? 'checked' : '' }} />
            <label class="form-check-label" for="is_public">
                Public Profile
            </label>
        </div>

        <button class="btn btn-primary mb-2" type="submit">Save Profile</button>


    </form>
    <div class="gap-5">
        <h1 class="">Danger Zone</h1>
        <form method="GET" id="change-password" class="d-flex flex-column justify-content-start align-items-start " action="/change-password">
            <div class="d-flex flex-column justify-content-start align-items-start">
                <button id="change-password-button" class="btn btn-danger" type="submit">Change Your Password</button>
            </form>
        </div>

        <form method="POST" class="d-flex flex-column justify-content-start align-items-start gap-" action="/profile/{{ $user->id }}">
            {{ csrf_field() }}
            @method('DELETE')

            <div class="d-flex flex-column justify-content-start align-items-start py-2">
                <button id="delete-button" class="btn btn-danger mb-2" type="submit">Delete your Account</button>
                <label for="delete-button fw-light">All your posts, comments, reactions and more will be made anonymous and
                    you will lose access to your account.</label>
            </div>
        </form>
    </div>


    </section>
    @endsection

    @section('footer')
    @include('partials.footer')
    @endsection

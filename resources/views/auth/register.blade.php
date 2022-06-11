@extends('layouts.app')

@section('header')
    @include('partials.headers.basic')
@endsection

@section('content')

    <div class="container-fluid d-flex flex-column justify-content-start align-items-center w-50 my-5">
        <div class="d-flex flex-column gap-5 shadow-sm bg-white p-3 h-50 w-50 p-4">
            <form method="POST" action="{{ route('register') }}" class="d-flex flex-column gap-4">
                {{ csrf_field() }}
                <div class="lh-1">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h1>Register</h1>
                        <span class="material-icons-big">
                            account_circle
                        </span>
                    </div>
                    <div class=" d-flex flex-row align-items-center gap-2">
                        <span>It's easy and simple</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-row gap-2">
                        <input class="form-control" type="text" placeholder="First Name" name="first-name"
                            value="{{ old('first-name') }}" required autofocus />
                        @if ($errors->has('first-name'))
                            <span class="error">
                                {{ $errors->first('first-name') }}
                            </span>
                        @endif

                        <input type="text" class="form-control" placeholder="Last Name" name="last-name"
                            value="{{ old('last-name') }}" required autofocus />
                        @if ($errors->has('last-name'))
                            <span class="error">
                                {{ $errors->first('last-name') }}
                            </span>
                        @endif
                    </div>

                    <input id="email" type="email" class="form-control" placeholder="Email Address" name="email"
                        value="{{ old('email') }}" required />
                    @if ($errors->has('email'))
                        <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif

                    <input id="password" type="password" class="form-control" placeholder="Password" name="password"
                        required />
                    @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif

                    <input id="password-confirm" type="password" class="form-control" placeholder="Confirm Password"
                        name="password_confirmation" required />

                    <button class="btn btn-primary" type="submit">Register</button>
                </div>
                <br />
                Already have an account?
                <a class="link" href="{{ route('login') }}">Sign In</a>
            </form>
        </div>
    @endsection

    @section('footer')
        @include('partials.footer')
    @endsection

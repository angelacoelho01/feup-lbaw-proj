@extends('layouts.app')

@section('header')
@include('partials.headers.basic')
@endsection

@section('content')

<div class="container-fluid d-flex flex-row justify-content-center align-items-center w-50 my-5">
    <div class="d-flex flex-column gap-5 shadow-sm bg-white p-3 h-50 w-50 p-4">
        <form method="POST" action="{{ route('login') }}" class="d-flex flex-column justify-content-start gap-4">
            {{ csrf_field() }}

            <div class="lh-1">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <h1>Login</h1>
                    <span class="material-icons-big">
                        account_circle
                    </span>
                </div>
                <div class=" d-flex flex-row align-items-center gap-2">
                    <span>Welcome back</span>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <input class="form-control" type="email" placeholder="Email Address" name="email" value="{{ old('email') }}" required autofocus />
                @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
                @endif

                <input class="form-control" type="password" placeholder="Password" name="password" required />
                @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
                @endif
                <button class="btn btn-primary mb-2" style="width: 4rem;" type="submit">Login</button>
                <br />
                <div>
                    Don't have an account?
                    <a class="link" href="{{ route('register') }}">Sign Up</a>
                    or try
                    <a class="link" href="{{ route('home') }}">browsing Bacefook.</a>
                </div>
                <div>
                    Forgot your Password?
                    <a class="link" href="{{ route('password.request') }}">Recover it now.</a>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('footer')
@include('partials.footer')
@endsection
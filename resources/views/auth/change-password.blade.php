@extends('layouts.app')

@section('header')
@include('partials.headers.basic')
@endsection

@section('content')

<div class="container-fluid d-flex flex-row justify-content-center align-items-center w-50 my-5">
    <div class="d-flex flex-column gap-5 shadow-sm bg-white p-3 h-50 w-50 p-4">
        <form method="POST" action="/change-password" class="d-flex flex-column justify-content-start gap-4">
            {{ csrf_field() }}
            <div class="lh-1">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <h1>Change Your Password</h1>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <input class="form-control" type="password" placeholder="Old Password" name="password_old" value="{{ old('password_old') }}" required autofocus />
                @if ($errors->has('password_old'))
                <span class="error">
                    {{ $errors->first('password_old') }}
                </span>
                @endif

                <input id="password" type="password" class="form-control" placeholder="New Password" name="password" required />
                @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
                @endif

                <input id="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required />
                @if ($errors->has('password_confirmation'))
                <span class="error">
                    {{ $errors->first('password_confirmation') }}
                </span>
                @endif

                <button class="btn btn-primary mb-2" style="width: 6rem;" type="submit">Change</button>
                <br />
            </div>
        </form>


    </div>

</div>
@endsection

@section('footer')
@include('partials.footer')
@endsection
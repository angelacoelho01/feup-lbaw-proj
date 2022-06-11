@extends('layouts.app')

@section('header')
@if (Auth::check())
@include('partials.headers.authenticated_user')
@else
@include('partials.headers.visitor')
@endif
@endsection

@section('content')

<div class="d-flex justify-content-center w-100">
    <div class="d-flex flex-column justify-content-center align-items-center gap-5 p-5">
        <article class="d-flex flex-column justify-content-center py-5 gap-0 w-50 text-center">
            <h1>Text or Call Us:</h1>

            <h2>(+351) 123 456 789</h2>

            <p class="text">Wow, it looks like you have scored our number. Feel free to give
                us a call whenever you feel like it, with any questions, comments or concerns.
                Our team will be glad to hear from you!</p>

            <h1>Or, if you prefer E-mail:</h1>

            <ul>
                <li>
                    <p class="text">up201907021@edu.fe.up.pt</p>
                </li>
                <li>
                    <p class="text">up201907877@edu.fe.up.pt</p>
                </li>
                <li>
                    <p class="text">up201907549@edu.fe.up.pt</p>
                </li>
                <li>
                    <p class="text">up201907487@edu.fe.up.pt</p>
                </li>
            </ul>
            <p class="text">Any one of these is the way to go! We are always ready to answer your
                questions, dillemas, and overall curiosities! And if you just want to talk, we are here for you too.</p>
        </article>
    </div>
</div>


@endsection

@section('footer')
@include('partials.footer')
@endsection
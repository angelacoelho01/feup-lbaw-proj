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
            <h1>Hey There! Welcome to Bacefook</h1>

            <p class="text">Bacefook is the newest social network platform to go mainstream!
                We changed the game when it comes to interacting with your friends. Have
                doubts? Hop in and give it a try then!
                We have 3 types of users here. They are:
            </p>

            <ul>
                <li>
                    <p class="text"><b>Visitors:</b> They can only see what's
                        public: public posts, groups, profiles, and a generic
                        timeline with trending publications.</p>
                </li>
                <li>
                    <p class="text"><b>Authenticated users:</b> They can see and
                        interact with public posts or private posts from their
                        friends. They can also edit their profiles and manage
                        their friendships or group memberships.</p>
                </li>
                <li>
                    <p class="text"><b>Administrators:</b> They can see and
                        manage all user accounts.</p>
                </li>
            </ul>

            <h2>Main Features:</h2>

            <p class="text">From the Bacefook home page, visitors can log in (with
                email and password) or register a new account. If the login
                fails, an option to recover the password will be available.
            </p>
            <p class="text"> Administrators have total access to all the system's information.
                They can search, view, edit, ban, unban, and delete user
                accounts. Authenticated users will see a personalized timeline
                filled with posts from their friends and groups they can interact
                with by reacting or adding a comment.
            </p>
            <p class="text"> Once a user posts or
                comments on a publication, they should have the ability to edit,
                delete, and manage the visibility of what they sent. There will
                also be a personal user page, where the user's posts and profile
                information, such as name and profile picture, will be present.
            </p>
            <p class="text"> Being user connection the goal of the project, users can send
                friend requests and enter groups. Profiles and groups can be
                public or private. Private profiles and groups are only visible
                to friends or members. Group Owners can edit the group information
                and manage group members and publications. Group members can view
                the group's members and posts, post on the group, and leave the group.
            </p>
            <p class="text">Upon account deletion, shared user data (e.g., comments, reviews,
                likes) is kept but is made anonymous.
            </p>

            <h1>Our Values:</h1>

            <p class="text">
                Bacefook is a space where people can share their thoughts and ideas
                with their friends and family through text, images, videos, or
                links. Having inclusion as one of the ground rules, Bacefook allows
                its users to connect and provides a safe space to share their
                thoughts. As such, our main values are:
            </p>
            <ul class="d-flex justify-content-evenly">
                <li>
                    <h3><b>Freedom</b></h3>
                </li>
                <li>
                    <h3><b>Respect</b></h3>
                </li>
                <li>
                    <h3><b>Inclusion</b></h3>
                </li>
            </ul>

            <h1>Our Mission:</h1>

            <p class="text">
                Here at Bacefook, we strive to provide you with the smoothest
                experience possible. We want our app to be a place where you
                can relax at the end of a stressfull day at work, and catch up
                on all your friends and family. You can also check it during work,
                we promise we won't tell your boss.
            </p>
        </article>
    </div>
</div>


@endsection

@section('footer')
@include('partials.footer')
@endsection
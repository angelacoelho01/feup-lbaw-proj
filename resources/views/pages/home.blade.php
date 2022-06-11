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

        <div class="container-fluid d-flex flex-row justify-content-between">

            <div class="d-flex col-2 flex-column justify-content-start align-items-start">
                @if (Auth::check())
                    @include('partials.sidebars.authenticated_user')
                @else
                    @include('partials.sidebars.visitor')
                @endif
            </div>


            <div class="d-flex col-5 justify-content-center">
                <div class="d-flex flex-column gap-3 justify-self-center">
                    @can('createPost', $user)
                        @include('partials.posts.create', ['group' => null])
                    @endcan
                    <div id="post-data" class="d-flex flex-column gap-3">
                        @each('partials.posts.post', $posts, 'post')
                    </div>
                </div>
            </div>
            <div class="d-flex col-2"></div>
        </div>

        <script type="text/javascript">
            var page = 1;

            document.addEventListener('scroll', function(e) {
                if (window.pageYOffset + window.innerHeight >= window.document.documentElement.scrollHeight) {
                    page++;
                    loadMoreData(page);
                }
            });

            async function loadMoreData(page) {
                await fetch('?page=' + page, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    method: 'GET',
                    credentials: "same-origin",
                }).then(async (data) => {
                    return await data.json();
                }).then((data) => {
                    let elements = document.querySelectorAll('.ajax-load');
                    if (data.html == " ") {

                        elements.forEach((element, key) => {
                            element.innerHTML = "No more records found";
                        });
                        return;
                    }

                    elements.forEach((element, key) => {
                        element.style.setProperty("display", "none", "important");
                    });
                    document.getElementById("post-data").insertAdjacentHTML('beforeend', data.html);
                }).then((data) => {
                        document.querySelectorAll(".fire-btn").forEach((element, key) => {
                            element.addEventListener('click', sendFireRequest);
                        });
                        document.querySelectorAll(".comment-fire-btn").forEach((element, key) => {
                            element.addEventListener('click', sendFireRequestComment);
                        });
                        document.querySelectorAll(".ice-btn").forEach((element, key) => {
                            element.addEventListener('click', sendIceRequest);
                        });
                        document.querySelectorAll(".comment-ice-btn").forEach((element, key) => {
                            element.addEventListener('click', sendIceRequestComment);
                        });
                        document.querySelectorAll(".post-reply-field").forEach((element, key) => {
                            element.addEventListener('keydown', event => {
                                if (event.key === "Enter" && !event.shiftKey) {
                                    event.preventDefault();
                                    element.closest(".post-reply-form").submit();
                                }
                            });
                        });
                        document.querySelectorAll(".comment-reply").forEach((element, key) => {
                            element.style.setProperty("display", "none", "important");
                        });
                        document.querySelectorAll(".comment-reply-field").forEach((element, key) => {
                            element.addEventListener("keydown", event => {
                                if (event.key === "Enter" && !event.shiftKey) {
                                    event.preventDefault();
                                    element.closest(".comment-reply-form").submit();
                                }
                            });
                        });

                    }

                );;

            }
        </script>

    @endsection

    @section('footer')
        <div class="mt-3">
            @include('partials.footer')
        </div>
    @endsection

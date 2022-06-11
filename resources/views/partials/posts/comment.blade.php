@can('view', $comment)
    <div class="d-flex flex-column bg-white gap-2">

        <div class="d-flex flex-column justify-content-start align-items-start">


            <div class="d-flex flex-column align-items-center gap-3" style="font-size:16px;">

                <div class="d-flex flex-row gap-3">
                    <a href="{{ url('/profile/' . $comment->profile->id) }}">
                        <img class="rounded-circle" src="{{ Storage::url($comment->profile->profile_pic) }}" width="30"
                            height="30">
                    </a>
                    <div class="d-flex flex-column">

                        <div class="d-flex flex-row align-items-center gap-2">
                            <a href="{{ url('/profile/' . $comment->profile->id) }}">
                                {{ $comment->profile->name }}
                            </a>
                            <div class="text-secondary" style="font-size: 0.75rem;">
                                {{ date('d-m-Y H:i', strtotime($comment->date_time)) }} </div>
                        </div>

                        <div class="d-flex flex-row align-items-center gap-2">
                            <div class="bg-nord-6 border-0 w-100" style="border-radius: 1rem;">
                                <p class="text-break text-wrap m-2 " style="font-size: 1rem;">
                                    {{ $comment->content }}</p>
                            </div>
                            @can('update', $comment)
                                <div class="dropdown">
                                    <div class="text-secondary" type="button"
                                        id="comment-{{ $comment->id }}-settings-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        ...
                                    </div>
                                    <ul class="dropdown-menu" aria-labelledby="comment-{{ $comment->id }}-settings-dropdown">
                                        <li><a class="dropdown-item" href="#" id="edit-button-{{ $comment->id }}"
                                                type="button" data-bs-toggle="modal"
                                                data-bs-target="#edit-comment-{{ $comment->id }}-modal">Edit
                                                Comment</a>
                                        </li>
                                        <li><a class="dropdown-item" id="edit-button-{{ $comment->id }}" type="button"
                                                href="{{ route('comment.delete', ['comment_id' => $comment->id]) }}">Delete
                                                Comment</a>
                                        </li>
                                    </ul>
                                </div>
                            @endcan

                        </div>

                        <div class="d-flex justify-content-start align-icons-center gap-1 text-secondary">

                            @can('create', $comment)
                                <div>
                                    <button class="bg-white border-0 text-secondary" id="reply-icon-{{ $comment->id }}"
                                        onclick="toggle_visibility('comment-{{ $comment->id }}-reply')"
                                        style="font-size: 1rem;">
                                        Reply
                                    </button>
                                </div>
                            @endcan


                            @can('create', App\Models\Reaction::class)
                                <div style="font-size: 1rem;" class="d-flex flex-row gap-2 text-secondary">
                                    <meta name="csrftoken" content="{{ csrf_token() }}">
                                    @php
                                        $positive_total = $comment->reactions->where('is_positive', '1')->count();
                                        $negative_total = $comment->reactions->where('is_positive', '0')->count();
                                        
                                        $positive_user = 0;
                                        $negative_user = 0;
                                        
                                        $positive_user = $comment->reactions
                                            ->where('is_positive', '1')
                                            ->where('user_id', '=', Auth::user()->id)
                                            ->count();
                                        $negative_user = $comment->reactions
                                            ->where('is_positive', '0')
                                            ->where('user_id', '=', Auth::user()->id)
                                            ->count();
                                        
                                    @endphp
                                    <div class="d-flex flex-row align-items-center gap-1 fire-reaction">
                                        <button id="fire-comment-{{ $comment->id }}" type="button"
                                            class="comment-fire-btn material-icons bg-white border-0 {{ $positive_user != 0 ? 'fire-reaction-on' : '' }}"
                                            name="type" value="positive" style="font-size: 0.8rem;">whatshot</button>
                                        <span id="fire-comment-{{ $comment->id }}-count" style="font-size: 1rem;"
                                            class="{{ $positive_user != 0 ? 'fire-reaction-on' : '' }}">{{ $positive_total }}</span>
                                    </div>

                                    <div class="d-flex flex-row align-items-center gap-1 ice-reaction">
                                        <button id="ice-comment-{{ $comment->id }}" type=button
                                            class="comment-ice-btn material-icons bg-white border-0  {{ $negative_user != 0 ? 'ice-reaction-on' : '' }}"
                                            name="type" value="negative" style="font-size: 1rem;">ac_unit</button>
                                        <span id="ice-comment-{{ $comment->id }}-count" style="font-size: 1rem;"
                                            class="{{ $negative_user != 0 ? 'ice-reaction-on' : '' }}">{{ $negative_total }}</span>
                                    </div>
                                </div>

                            @else
                                <div class="d-flex flex-row gap-4">
                                    @csrf
                                    @php
                                        $positive_total = $comment->reactions->where('is_positive', '1')->count();
                                        $negative_total = $comment->reactions->where('is_positive', '0')->count();
                                        
                                    @endphp
                                    <div class="d-flex flex-row align-items-center gap-1">
                                        <div class="material-icons bg-white border-0" style="font-size: 1rem;">whatshot</div>
                                        <span style="font-size: 1rem;">{{ $positive_total }}</span>
                                    </div>

                                    <div class="d-flex flex-row align-items-center gap-1">
                                        <div class="material-icons bg-white border-0" style="font-size: 1rem;">ac_unit</div>
                                        <span style="font-size: 1rem;">{{ $negative_total }}</span>
                                    </div>
                                </div>
                            @endcan


                            @include('partials.comments.edit_modal', ['$comment' => $comment])

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex flex-row gap-3">
            <div class="bg-nord-6" style="width: 2px;"></div>
            <div class="d-flex flex-column gap-3">
                @can('create', $comment)
                    <div class="d-flex flex-row align-items-center gap-3 comment-reply"
                        id="comment-{{ $comment->id }}-reply">
                        <a href="{{ url('/profile/' . Auth::user()->id) }}">
                            <img class="rounded-circle" src="{{ Storage::url(Auth::user()->profile_pic) }}" width="30"
                                height="30">
                        </a>
                        <form method="POST" action="{{ route('comments.comments.store', ['comment_id' => $comment->id]) }}"
                            class="form-group comment-reply-form" id="comment-{{ $comment->id }}-reply-form">
                            @csrf
                            <textarea name='comment-text'
                                class="comment-reply-field form-control rounded rounded-pill bg-nord-6 border-0"
                                id="comment-reply-field-{{ $comment->id }}" placeholder="Write a comment..." cols="100"
                                rows="1" style="resize: none; height: 1rem;"></textarea>
                        </form>
                    </div>
                @endcan
                @each('partials.posts.comment', $comment->comments, 'comment')
            </div>
        </div>

    </div>

    @can('create', App\Models\Comment::class)
        <script>
            reply = document.getElementById("comment-{{ $comment->id }}-reply");
            reply.style.setProperty("display", "none", "important");

            document.getElementById("comment-reply-field-{{ $comment->id }}").addEventListener("keydown", event => {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    document.getElementById("comment-{{ $comment->id }}-reply-form").submit();
                }
            });

            function toggle_visibility(e) {
                element = document.getElementById(e);
                console.log(element);
                if (element.style.display != 'none') {
                    element.style.setProperty("display", "none", "important");
                } else {
                    element.style.setProperty("display", "flex", "important");
                }
            }
        </script>
    @endcan

@endcan

@can('create', App\Models\Reaction::class)
    <script>
        let fireComment{{ $comment->id }} = document.getElementById("fire-comment-{{ $comment->id }}");
        let iceComment{{ $comment->id }} = document.getElementById("ice-comment-{{ $comment->id }}");

        fireComment{{ $comment->id }}.addEventListener('click', sendFireRequestComment);
        iceComment{{ $comment->id }}.addEventListener('click', sendIceRequestComment);

        async function sendFireRequestComment() {
            const commentId = this.id.replace("fire-comment-", "");
            let url = "{{ route('comments.reactions.store', ['comment_id' => $comment->id]) }}";
            url = url.replace("{{ $comment->id }}", commentId);
            const token = document.querySelector('meta[name = "csrftoken"]').getAttribute('content');
            await fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": token,
                    },
                    method: 'POST',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        type: "positive"
                    })
                })
                .then(async (data) => {
                    return await data.json();
                })
                .then((data) => {
                    if (data.reaction == "none") {
                        document.getElementById(this.id).classList.remove('fire-reaction-on');
                        document.getElementById(this.id + "-count").classList.remove('fire-reaction-on');
                        document.getElementById(this.id + "-count").innerHTML = data.positive_count;
                    } else if (data.reaction == "positive") {
                        let iceId = this.id.replace("fire", "ice");
                        document.getElementById(this.id).classList.add('fire-reaction-on');
                        document.getElementById(this.id + "-count").classList.add('fire-reaction-on');
                        document.getElementById(this.id + "-count").innerHTML = data.positive_count;
                        document.getElementById(iceId).classList.remove('ice-reaction-on');
                        document.getElementById(iceId + "-count").classList.remove('ice-reaction-on');
                        document.getElementById(iceId + "-count").innerHTML = data.negative_count;
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });

        }

        // async function sendIceRequestComment() {
        //     const url = "{{ route('comments.reactions.store', ['comment_id' => $comment->id]) }}";
        //     const token = document.querySelector('meta[name = "csrftoken"]').getAttribute('content');
        //     await fetch(url, {
        //             headers: {
        //                 "Content-Type": "application/json",
        //                 "Accept": "application/json, text-plain, */*",
        //                 "X-Requested-With": "XMLHttpRequest",
        //                 "X-CSRF-Token": token,
        //             },
        //             method: 'POST',
        //             credentials: "same-origin",
        //             body: JSON.stringify({
        //                 type: "negative"
        //             })
        //         })
        //         .then(async (data) => {
        //             return await data.json();
        //         })
        //         .then((data) => {
        //             if (data.reaction == "none") {
        //                 document.getElementById("ice-comment-{{ $comment->id }}").classList.remove(
        //                     'ice-reaction-on');
        //                 document.getElementById("ice-comment-{{ $comment->id }}-count").classList.remove(
        //                     'ice-reaction-on');
        //                 document.getElementById("ice-comment-{{ $comment->id }}-count").innerHTML = data
        //                     .negative_count;
        //             } else if (data.reaction == "negative") {
        //                 document.getElementById("ice-comment-{{ $comment->id }}").classList.add(
        //                     'ice-reaction-on');
        //                 document.getElementById("ice-comment-{{ $comment->id }}-count").classList.add(
        //                     'ice-reaction-on');
        //                 document.getElementById("ice-comment-{{ $comment->id }}-count").innerHTML = data
        //                     .negative_count;
        //                 document.getElementById("fire-comment-{{ $comment->id }}").classList.remove(
        //                     'fire-reaction-on');
        //                 document.getElementById("fire-comment-{{ $comment->id }}-count").classList.remove(
        //                     'fire-reaction-on');
        //                 document.getElementById("fire-comment-{{ $comment->id }}-count").innerHTML = data
        //                     .positive_count;
        //             }
        //         })
        //         .catch(function(error) {
        //             console.error(error);
        //         });

        // }

        async function sendIceRequestComment() {
            const commentId = this.id.replace("ice-comment-", "");
            let url = "{{ route('comments.reactions.store', ['comment_id' => $comment->id]) }}";
            url = url.replace("{{ $comment->id }}", commentId);
            const token = document.querySelector('meta[name = "csrftoken"]').getAttribute('content');
            await fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": token,
                    },
                    method: 'POST',
                    credentials: "same-origin",
                    body: JSON.stringify({
                        type: "negative"
                    })
                })
                .then(async (data) => {
                    return await data.json();
                })
                .then((data) => {
                    if (data.reaction == "none") {
                        document.getElementById(this.id).classList.remove('ice-reaction-on');
                        document.getElementById(this.id + "-count").classList.remove('ice-reaction-on');
                        document.getElementById(this.id + "-count").innerHTML = data.negative_count;
                    } else if (data.reaction == "negative") {
                        let fireId = this.id.replace("ice", "fire");
                        document.getElementById(this.id).classList.add('ice-reaction-on');
                        document.getElementById(this.id + "-count").classList.add('ice-reaction-on');
                        document.getElementById(this.id + "-count").innerHTML = data.negative_count;
                        document.getElementById(fireId).classList.remove('fire-reaction-on');
                        document.getElementById(fireId + "-count").classList.remove('fire-reaction-on');
                        document.getElementById(fireId + "-count").innerHTML = data.positive_count;
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });

        }
    </script>
@endcan

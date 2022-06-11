@can('view', $post)

    <div class="container d-flex flex-column shadow-sm bg-white p-4 rounded rounded-3">

        <div class="d-flex flex-row justify-content-between">

            <div class="d-flex flex-row gap-3">
                <div>
                    @if ($post->profile !== null)
                        <a href="{{ url('/profile/' . $post->profile->id) }}">
                            <img class="rounded-circle" src="{{ Storage::url($post->profile->profile_pic) }}" width="60"
                                height="60">
                        </a>
                    @else
                        <img class="rounded-circle" src="{{ Storage::url('images/profiles/default.jpg') }}" width="60"
                            height="60">
                    @endif
                </div>
                <div class="d-flex flex-column">
                    @if ($post->profile !== null)
                        <a href="{{ url('/profile/' . $post->profile->id) }}">
                            <h5> {{ $post->profile->name }} </h5>
                        </a>
                    @else
                        <h5>[deleted]</h5>
                    @endif
                    <p>{{ date('d-m-Y H:i', strtotime($post->date_time)) }} </p>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                @can('update', $post)
                    <div class="dropdown">
                        <a class="dropdown-toggle" type="button" id="post-{{ $post->id }}-settings-dropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="post-{{ $post->id }}-settings-dropdown">
                            <li><a class="dropdown-item" href="#" id="edit-button-{{ $post->id }}" type="button"
                                    data-bs-toggle="modal" data-bs-target="#edit-post-{{ $post->id }}-modal">Edit
                                    Post</a>
                            </li>
                            <li><a class="dropdown-item" id="edit-button-{{ $post->id }}" type="button"
                                    href="{{ route('post.delete', ['post_id' => $post->id]) }}">Delete Post</a>
                            </li>
                        </ul>
                    </div>
                @endcan
                @include('partials.posts.edit_modal', ['$post' => $post])
            </div>

        </div>

        <p class="text-break text-wrap" style="font-size:18px;">{{ $post->post_text }}</p>

        <div class="">
            @if ($post->multimediaContent->isNotEmpty())
                <div id="post-media-slideshow-{{ $post->id }}" class="carousel slide w-100" data-bs-ride="none">
                    <div class="carousel-inner">
                        @foreach ($post->multimediaContent as $idx => $media)
                            <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                                @if ($media->content_type == 'Photo')
                                    <img src="{{ Storage::url($media->content) }}" class="d-block w-100"
                                        alt="slide-{{ $idx }}">
                                @else
                                    <video controls class="w-100">
                                        <source src="{{ Storage::url($media->content) }}" type="video/mp4">
                                        <source src="{{ Storage::url($media->content) }}" type="video/webm">
                                        <source src="{{ Storage::url($media->content) }}" type="video/ogg">
                                    </video>

                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button"
                        data-bs-target="#post-media-slideshow-{{ $post->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button"
                        data-bs-target="#post-media-slideshow-{{ $post->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="d-flex flex-column">
            <span class="border-bottom w-100 my-3"> </span>

            <div class="d-flex flex-row justify-content-between">

                @can('create', App\Models\Reaction::class)
                    <div class="d-flex flex-row gap-4">
                        <meta name="csrftoken" content="{{ csrf_token() }}">
                        @php
                            $positive_total = $post->reactions->where('is_positive', '1')->count();
                            $negative_total = $post->reactions->where('is_positive', '0')->count();
                            
                            $positive_user = 0;
                            $negative_user = 0;
                            
                            $positive_user = $post->reactions
                                ->where('is_positive', '1')
                                ->where('user_id', '=', Auth::user()->id)
                                ->count();
                            $negative_user = $post->reactions
                                ->where('is_positive', '0')
                                ->where('user_id', '=', Auth::user()->id)
                                ->count();
                            
                        @endphp
                        <div class="d-flex flex-row align-items-center gap-2 fire-reaction">
                            <button id="fire-post-{{ $post->id }}" type="button"
                                class="fire-btn material-icons bg-white border-0 {{ $positive_user != 0 ? 'fire-reaction-on' : '' }}"
                                name="type" value="positive">whatshot</button>
                            <span id="fire-post-{{ $post->id }}-count"
                                class="{{ $positive_user != 0 ? 'fire-reaction-on' : '' }}">{{ $positive_total }}</span>
                        </div>

                        <div class="d-flex flex-row align-items-center gap-2 ice-reaction">
                            <button id="ice-post-{{ $post->id }}" type="button"
                                class="ice-btn material-icons bg-white border-0  {{ $negative_user != 0 ? 'ice-reaction-on' : '' }}"
                                name="type" value="negative">ac_unit</button>
                            <span id="ice-post-{{ $post->id }}-count"
                                class="{{ $negative_user != 0 ? 'ice-reaction-on' : '' }}">{{ $negative_total }}</span>
                        </div>
                    </div>

                @else
                    <div class="d-flex flex-row gap-4">
                        @csrf
                        @php
                            $positive_total = $post->reactions->where('is_positive', '1')->count();
                            $negative_total = $post->reactions->where('is_positive', '0')->count();
                            
                        @endphp
                        <div class="d-flex flex-row align-items-center gap-2">
                            <div class="material-icons bg-white border-0">whatshot</div>
                            <span>{{ $positive_total }}</span>
                        </div>

                        <div class="d-flex flex-row align-items-center gap-2">
                            <div class="material-icons bg-white border-0">ac_unit</div>
                            <span>{{ $negative_total }}</span>
                        </div>
                    </div>
                @endcan


                <div class="d-flex flex-row gap-4">
                    <div class="d-flex flex-row align-items-center gap-2">
                        <span>{{ $post->comments->count() }}</span>
                        <span class="material-icons">
                            <a href="{{ route('post.show', ['id' => $post->id]) }}">
                                insert_comment
                            </a>
                        </span>
                    </div>
                </div>
            </div>

            <span class="border-bottom w-100 my-3"> </span>
        </div>

        <div class="d-flex flex-column align-items-start gap-3">

            @if (Auth::check())
                <div class="d-flex flex-row align-items-center gap-3">
                    <a href="{{ url('/profile/' . Auth::user()->id) }}">
                        <img class="rounded-circle" src="{{ Storage::url(Auth::user()->profile_pic) }}" width="30"
                            height="30">
                    </a>
                    <form method="POST"
                        action="{{ route('posts.comments.store', ['post_id' => $post->id, 'comment_id' => null]) }}"
                        class="form-group post-reply-form" id="post-{{ $post->id }}-reply-form">
                        @csrf
                        <textarea name='comment-text' class="form-control rounded rounded-pill bg-nord-6 border-0 post-reply-field"
                            id="post-reply-field-{{ $post->id }}" placeholder="Write a comment..." cols="100" rows="1"
                            style="resize: none; height: 1rem;"></textarea>
                    </form>
                </div>
            @endif


            <div class="d-flex flex-column align-items-start gap-3">
                @each('partials.posts.comment', $post->comments, 'comment')
            </div>

        </div>


    </div>

@endcan

<script type="text/javascript">
    @can('create', App\Models\Comment::class)
    
        document.getElementById("post-reply-field-{{ $post->id }}").addEventListener("keydown", event => {
        if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault();
        document.getElementById("post-{{ $post->id }}-reply-form").submit();
        }
        });
    
    
    @endcan


    @can('create', App\Models\Reaction::class)
    
    
        let firePost{{ $post->id }} = document.getElementById("fire-post-{{ $post->id }}");
        let icePost{{ $post->id }} = document.getElementById("ice-post-{{ $post->id }}");
    
        firePost{{ $post->id }}.addEventListener('click', sendFireRequest);
        icePost{{ $post->id }}.addEventListener('click', sendIceRequest);
    
        async function sendFireRequest() {
            const postId = this.id.replace("fire-post-", "");
            let url = "{{ route('posts.reactions.store', ['post_id' => $post->id]) }}";
            url = url.replace("{{ $post->id }}", postId);
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
    
        async function sendIceRequest() {
            const postId = this.id.replace("ice-post-", "");
            let url = "{{ route('posts.reactions.store', ['post_id' => $post->id]) }}";
            url = url.replace("{{ $post->id }}", postId);
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
    @endcan
</script>

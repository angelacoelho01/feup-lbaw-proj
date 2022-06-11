@can('view', $comment)
    <div class="d-flex flex-column bg-white gap-2">

        <div class="d-flex flex-column justify-content-center align-items-start">


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
                                {{ date('d-m-Y H:i', strtotime($comment->date_time)) }} 
                            </div>
                            <a href="{{url('/post/'.$comment->post()->get()->first()->id)}}">Go to post...</a>
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

                                <div class="d-flex flex-row gap-4">
                                    @csrf
                                    @php
                                        $positive_total = $comment->reactions->where('is_positive', '1')->count();
                                        $negative_total = $comment->reactions->where('is_positive', '0')->count();
                                        
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
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endcan

<div class="d-flex rounded-3 shadow-sm bg-white">
    <div class="d-flex flex-row align-items-center gap-3 m-3 w-100">
        <img class="rounded-circle" src="{{ Storage::url(Auth::user()->profile_pic) }}" width="40" height="40">
        <div class="form-group w-100">
            <button class="text-start form-control rounded rounded-pill bg-nord-6 border-0 btn-block w-100" data-bs-toggle="modal" data-bs-target="#create-post-modal"">{{ "What's up, " . Auth::user()->name . '?' }}</button>
        </div>
    </div>
</div>

<div class=" modal fade" id="create-post-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-post-modal-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="create-post-modal-label">Create Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form action="
                                @if ($group === null)
                                  {{route('post.create')}}
                                @else
                                    {{route('post.create', ['group_id' => $group->id]);}}

                                @endif
                                " method="post" class="d-flex flex-column gap-3 align-items-end" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <textarea name='post-text' class="form-control border-0" id="post-text-field" placeholder="What's up, {{ Auth::user()->name }}" cols="100" rows="5" style="resize: none;"></textarea>
                                </div>
                                <div class="container d-flex flex-row justify-content-between p-0">
                                    <div class="form-group d-flex flex-column gap-2">
                                        <div class="form-group d-flex flex-row gap-2 align-items-center">
                                            <span class="material-icons">
                                                insert_photo
                                            </span>
                                            <input name="post-media[]" class="form-control border-0" type="file" id="post-media-field" accept="image/* video/*" multiple>
                                        </div>
                                        <div class="form-group d-flex flex-row gap-2 align-items-center">
                                            <span class="material-icons">
                                                visibility
                                            </span>
                                            <select class="form-select border-0 w-50" name="post-visibility" aria-label="Default select example" id="create-post-visibility">
                                                <option value="public">Public</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary form-control" type="submit" value="Post">
                                    </div>
                                </div>
                                <input type="hidden" value="{{ Session::token() }}" name="_token">
                            </form>

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
        </div>

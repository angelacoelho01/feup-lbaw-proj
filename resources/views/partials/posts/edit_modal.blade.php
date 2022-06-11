<div class="modal fade" id="edit-post-{{ $post->id }}-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="edit-post-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-post-{{ $post->id }}-modal-label">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('post.update', ['post_id' => $post->id]) }}" method="post"
                    class="d-flex flex-column gap-3 align-items-end" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <textarea name='post-text' class="form-control border-0"
                            id="post-{{ $post->id }}-text-field" required cols="100" rows="5"
                            style="resize: none;">{{ old('post-text') ?? $post->post_text }}</textarea>
                    </div>
                    <div class="form-group d-flex flex-row gap-2 align-items-center">
                        <span class="material-icons">
                            visibility
                        </span>
                        <select class="form-select" name="post-visibility" aria-label="Default select example"
                            id="create-post-{{ $post->id }}-visibility">
                            <option value="public" @if ($post->is_public) selected @endif>Public</option>
                            <option value="private" @if (!$post->is_public) selected @endif>Private</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" value="Save Changes">Save Changes</button>
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

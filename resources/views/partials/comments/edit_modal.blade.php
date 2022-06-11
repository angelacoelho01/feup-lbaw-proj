<div class="modal fade" id="edit-comment-{{ $comment->id }}-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="edit-post-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-comment-{{ $comment->id }}-modal-label">Edit Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('comment.update', ['comment_id' => $comment->id]) }}" method="post"
                    class="d-flex flex-column gap-3 align-items-end" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <textarea name='comment-text' class="form-control border-0" id="comment-{{ $comment->id }}-text-field"
                            required cols="100" rows="5"
                            style="resize: none;">{{ old('post-text') ?? $comment->content }}</textarea>
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

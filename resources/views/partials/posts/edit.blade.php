<form action="{{ route('post.update', ['post_id' => $post->id]) }}" method="post"
    class="d-flex flex-column gap-2 align-items-end" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="form-group">
        <textarea name='post-text' class="form-control" id="post-{{ $post->id }}-text-field" cols="100" rows="5"
            style="resize: none;">{{ old('post-text') ?? $post->post_text }}</textarea>
    </div>
    <div class="form-group d-flex flex-row align-items-center">
        <span class="material-icons">
            visibility
        </span>
        <select class="form-select border-0" name="post-visibility" aria-label="Default select example"
            id="create-post-visibility">
            <option value="public">Public</option>
            <option value="private">Private</option>
        </select>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="form-group">
        <button class="btn btn-primary" type="submit" value="Save Changes">Save Changes</button>
    </div>
    <input type="hidden" value="{{ Session::token() }}" name="_token">
</form>

<div class="d-flex justify-content-between">
    <div >
        <img class="rounded-circle" src="{{ Storage::url($member->profile_pic) }}" alt="Profile picture" width="60" height="60"/>
        <span class="ps-2">
            <a href="{{ url('profile/' . $member->id) }}">
                {{ $member->name }}
            </a>
        </span>
    </div>
</div>

@php
$group = $group
->userGroup()
->get()
->first();
@endphp
<div class="">
    <img class="rounded-circle" src="{{ Storage::url($group->group_pic) }}" alt="Group Picture" width="60" height="60"/>
    <span class="ps-2">
        <a href="/group/{{ $group->id }}">
            {{ $group->name }}
        </a>
    </span>
</div>
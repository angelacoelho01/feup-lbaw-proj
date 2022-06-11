<div class="d-flex flex-row justify-content-between">
    <div class="d-flex align-items-center">
        <p> {{$joinRequest->profile()->get()->first()->name}} </p>
    </div>
    <div class="align-items-start">
        <form method="POST" action="{{ route('group.answerJoinRequest', ['joinRequest' => $joinRequest->id]) }}" class="d-flex flex-row">
            @csrf
            <button type=submit class="material-icons bg-white border-0" name="type" value="accept">check</button>
            <button type=submit class="material-icons bg-white border-0" name="type" value="decline">close</button>
        </form>
    </div>
</div>

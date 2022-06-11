<div class="d-flex justify-content-start">
    <nav class="navbar d-flex flex-column justify-content-start align-items-start p-2 gap-4">

        <a class="nav-item" href="/profile/{{ Auth::user()->id }}">
            <div class="d-flex flex-row align-items-center gap-3">
                <img class="rounded-circle" src="{{ Storage::url(Auth::user()->profile_pic) }}" width="25" height="25" />
                <span> {{ Auth::user()->name }} </span>
            </div>
        </a>
        <form method="GET" id="get-friends" action="/profile/{{Auth::user()->id}}/friends" class="nav-item d-flex flex-column justify-content-start align-items-start gap-4">

            <button type="submit" class="bg-nord-6 border-0" name="friends" value="trending">
                    <div class="d-flex flex-row align-items-center gap-3">
                        <span class="material-icons">people</span>
                        <span> Friends </span>
                    </div>
            </button>
        </form>

        <form method="GET" id="get-friends" action="/profile/{{Auth::user()->id}}/groups" class="nav-item d-flex flex-column justify-content-start align-items-start gap-4">
            <button type="submit" class="bg-nord-6 border-0" name="friends" value="trending">
                    <div class="d-flex flex-row align-items-center gap-3">
                        <span class="material-icons">groups</span>
                        <span> Groups </span>
                    </div>
            </button>
        </form>


        <form method="GET" id="choose-posts" action="/home" class="nav-item d-flex flex-column justify-content-start align-items-start gap-4">
            <button type="submit" class="bg-nord-6 border-0" name="sorting" value="trending">
                    <div class="d-flex flex-row align-items-center gap-3">
                        <span class="material-icons">trending_up</span>
                        <span> Trending </span>
                    </div>
            </button>

            <button type="submit" class="bg-nord-6 border-0" name="sorting" value="recent">
                    <div class="d-flex flex-row align-items-center gap-3">
                        <span class="material-icons">schedule</span>
                        <span> Recent </span>
                    </div>
            </button>
        </form>
    </nav>
</div>

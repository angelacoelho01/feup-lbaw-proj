<nav id="navbar_top" class="navbar navbar-nav navbar-expand-lg bg-white shadow-sm mb-3">
    <div class="container-fluid row">
        <div class="d-flex col-2 justify-content-start">

            <form method="GET" action="{{ route('search') }}" class="d-flex flex-row gap-3 align-items-center">
                {{ csrf_field() }}
                <span class="material-icons">
                    search
                </span>
                <input class="form-control rounded rounded-pill bg-nord-6 border-0" type="search" name="search"
                    placeholder="Type to search..." aria-label="Search">
            </form>

        </div>

        <div class="d-flex col-2 justify-content-center">
            <a href={{ url('home') }}>
            <img src="{{ Storage::url('logo_new.svg') }}" alt="" width="130">
            </a>
        </div>

        <div class="d-flex col-2 justify-content-end">
            <div class="d-flex flex-row align-items-center gap-3">
                <a class="btn btn-primary" href="{{ url('/login') }}">Login</a>
                <a class="btn btn-primary" href="{{ url('/register') }}">Register</a>
            </div>
        </div>

    </div>
</nav>

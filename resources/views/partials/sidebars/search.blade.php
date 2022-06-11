<div class="sticky-top d-flex flex-column justify-content-start align-items-stretch w-100" style="top: 5rem;">
    <div class="d-flex flex-column gap-3 rounded shadow-sm bg-white p-3">
        <div class="d-flex flex-row align-items-center justify-content-start p-2 gap-3 mb-1">
            <span class="material-icons">filter_alt</span>
            <span class="fs-4 fw-bold"> Choose Filter </span>
        </div>
        <nav class="d-flex flex-column align-items-between p-2 gap-4">
            <div class="d-flex flex-row justify-content-between gap-3">
                <div class="d-flex flex-row gap-3 align-items-center">
                    <span class="material-icons">filter_none</span>
                    <span> All </span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="all" name="all">
                    <label class="form-check-label" for="all"></label>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-between gap-3">
                <div class="d-flex flex-row gap-3 align-items-center">
                    <span class="material-icons">person</span>
                    <span> Profiles </span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="profiles" name="profiles">
                    <label class="form-check-label" for="profiles"></label>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-between gap-3">
                <div class="d-flex flex-row gap-3 align-items-center">
                    <span class="material-icons">groups</span>
                    <span> Groups </span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="groups" name="groups">
                    <label class="form-check-label" for="groups"></label>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-between gap-3">
                <div class="d-flex flex-row gap-3 align-items-center">
                    <span class="material-icons">feed</span>
                    <span> Posts </span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="posts" name="posts">
                    <label class="form-check-label" for="posts"></label>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-between gap-3">
                <div class="d-flex flex-row gap-3 align-items-center">
                    <span class="material-icons">comment</span>
                    <span> Comments </span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="comments" name="comments">
                    <label class="form-check-label" for="comments"></label>
                </div>
            </div>
        </nav>
        <div class="p-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</div>
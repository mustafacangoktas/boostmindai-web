<nav class="d-flex justify-content-between align-items-center py-3">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <button class="sidebar-burger d-md-none" aria-label="Open sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <a class="align-items-center d-none d-md-flex" href="/">
            <img src="/assets/img/branding/logo.webp" alt="Logo" width="40" height="40" class="me-2">
            <span class="fs-5 fw-bold">BoostMindAI</span>
        </a>
        <div class="d-flex align-items-center gap-3 ">
            <div style="position: relative; width: 100%;" class="d-none d-md-block">
                <i data-feather="search" class="input-icon"></i>
                <input type="text" name="search" id="search" placeholder="Search..."
                       class="form-control search-input"
                       aria-label="Search">
            </div>
            <div class="user-dropdown">
                <div class="dropdown">
                    <button class="btn btn-link p-0 user-menu-toggle d-flex align-items-center gap-1" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="width: 100%;">
                        <span class="d-flex flex-column align-items-end">
                            <span>Mustafa Can Göktaş</span>
                            <span class="text-200">@mustqfacan</span>
                        </span>
                        <img src="/assets/img/chat/user-profile.jpg" alt="Profile Picture" width="48" height="48"
                             class="ms-2 rounded-circle">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="d-flex align-items-center dropdown-item gap-1 cursor-pointer">
                            <i data-feather="settings" style="width: 18px; height: 18px;"></i>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">Settings</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li class="d-flex align-items-center dropdown-item gap-1 cursor-pointer">
                            <i data-feather="log-out" style="width: 18px; height: 18px;"></i>
                            <a href="#">Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>



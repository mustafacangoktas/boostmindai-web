<div class="sidebar-overlay d-md-none"></div>
<div class="sidebar">
    <button class="sidebar-close d-md-none">
        <i data-feather="x" style="width: 24px; height: 24px;"></i>
    </button>
    <h3 class="fs-5">Chat History</h3>
    <nav class="sidebar__nav">
        <ul class="list-unstyled">
            <li class="chat-history-item sidebar__chat-item">
                <div class="d-flex align-items-center sidebar__chat-link">
                    <a href="#" class="flex-grow-1 text-decoration-none">
                        <div class="sidebar__chat-title">Today, May 1</div>
                        <div class="sidebar__chat-desc text-300">Short summary...</div>
                    </a>
                    <div class="sidebar__chat-actions">
                        <div class="dropdown">
                            <button class="btn btn-link p-0 sidebar__chat-menu" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i data-feather="more-horizontal"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li class="d-flex align-items-center dropdown-item gap-1 cursor-pointer">
                                    <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
                                    <a class="" href="#">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <li class="chat-history-item sidebar__chat-item active">
                <div class="d-flex align-items-center sidebar__chat-link">
                    <a href="#" class="flex-grow-1 text-decoration-none">
                        <div class="sidebar__chat-title">Yesterday, Apr 30</div>
                        <div class="sidebar__chat-desc text-300">Short summary...</div>
                    </a>
                    <div class="sidebar__chat-actions">
                        <div class="dropdown">
                            <button class="btn btn-link p-0 sidebar__chat-menu" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i data-feather="more-horizontal"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-start">
                                <li class="d-flex align-items-center dropdown-item gap-1 cursor-pointer">
                                    <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
                                    <a class="" href="#">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
    <div class="d-flex align-items-center justify-content-start gap-2 favorites__button">
        <div class="d-flex align-items-center justify-content-center rounded-circle border border-2 border-white"
             style="width: 38px; height: 38px;">
            <i data-feather="star" style="stroke-width: 2.5; width: 22px; height: 22px;"></i>
        </div>
        <div data-bs-toggle="modal" data-bs-target="#favoritesModal" style="cursor:pointer;">
            <h3 style="font-size: 1rem;" class="m-0 mb-1">My Favorites</h3>
            <p class="text-200 m-0" style="font-size: 0.8rem;">
                View your saved messages.
            </p>
        </div>
    </div>
</div>

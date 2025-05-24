<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" style="
    --bs-modal-bg: var(--bg-400) !important;
    --bs-modal-color: var(--text-50) !important;
    --bs-modal-header-border-color: var(--stroke-300) !important;
    --bs-modal-footer-border-color: var(--stroke-300) !important;
    --bs-modal-border-color: var(--stroke-300) !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" style="
    --bs-body-bg: var(--bg-300) !important;
    --bs-body-color: var(--text-50) !important;
    --bs-border-color: var(--stroke-300) !important;" id="username" placeholder="Enter your username">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" style="
    --bs-body-bg: var(--bg-300) !important;
    --bs-body-color: var(--text-50) !important;
    --bs-border-color: var(--stroke-300) !important;" id="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" style="
    --bs-body-bg: var(--bg-300) !important;
    --bs-body-color: var(--text-50) !important;
    --bs-border-color: var(--stroke-300) !important;" id="password" placeholder="Enter new password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade p-0" id="favoritesModal" tabindex="-1" aria-labelledby="favoritesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="
            --bs-modal-bg: var(--bg-400) !important;
            --bs-modal-color: var(--text-50) !important;
            --bs-modal-header-border-color: var(--stroke-300) !important;
            --bs-modal-footer-border-color: var(--stroke-300) !important;
            --bs-modal-border-color: var(--stroke-300) !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="favoritesModalLabel">My Favorites</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Favori mesajlar burada listelenecek -->
                <ul class="list-group">
                    <li class="list-group-item bg-transparent text-200 border-0">No favorites yet.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
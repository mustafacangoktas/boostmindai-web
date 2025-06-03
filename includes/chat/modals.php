<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content auth-card" novalidate style="
    --bs-modal-bg: var(--bg-400) !important;
    --bs-modal-color: var(--text-50) !important;
    --bs-modal-header-border-color: var(--stroke-300) !important;
    --bs-modal-footer-border-color: var(--stroke-300) !important;
    --bs-modal-border-color: var(--stroke-300) !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">
                    <?php echo t("settings_modal_title") ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4 form-floating">
                    <input type="text" id="registerName" class="form-control" name="name" placeholder=" "
                           minlength="2"
                           maxlength="50"
                           required
                           value="<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>"
                    />
                    <label for="registerName">
                        <?php echo t('register_fullname'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('register_name_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating">
                    <input type="password" id="oldPassword" name="old_password" class="form-control"
                           placeholder="" minlength="8" maxlength="32"/>
                    <label for="oldPassword">
                        <?php echo t('settings_modal_old_password'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('settings_modal_old_password_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating">
                    <input type="password" id="newPassword" name="password" class="form-control"
                           placeholder="" minlength="8" maxlength="32"/>
                    <label for="newPassword">
                        <?php echo t('settings_modal_new_password'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('settings_modal_new_password_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating">
                    <input type="password" id="newPasswordRepeat" class="form-control"
                           name="new_password_repeat"
                           placeholder="Repeat Password" minlength="8" maxlength="32"/>
                    <label for="newPasswordRepeat">
                        <?php echo t('settings_modal_new_password_repeat'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('settings_modal_new_password_mismatch'); ?></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.5rem 1.5rem;">
                    <?php echo t("settings_modal_cancel_button") ?>
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">
                    <?php echo t("settings_modal_save_button") ?>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const settingsModal = document.getElementById('settingsModal');
        const settingsForm = settingsModal.querySelector('form');

        settingsForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!settingsForm.checkValidity()) {
                settingsForm.classList.add('was-validated');
                return;
            }

            const formData = new FormData(settingsForm);

            if (formData.get('password') !== formData.get('new_password_repeat')) {
                alert('<?php echo t('settings_modal_new_password_mismatch'); ?>');
                return;
            }
            try {
                const response = await fetch('/api/auth/settings', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    location.reload(); // Reload to update user data
                } else {
                    alert(data.message || '<?php echo t('settings_modal_save_error'); ?>');
                }
            } catch (error) {
                console.error('Error updating settings:', error);
                alert('An error occurred while updating settings.');
            }
        });
    });
</script>

<div class="modal fade p-0" id="favoritesModal" tabindex="-1" aria-labelledby="favoritesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="
            --bs-modal-bg: var(--bg-400) !important;
            --bs-modal-color: var(--text-50) !important;
            --bs-modal-header-border-color: var(--stroke-300) !important;
            --bs-modal-footer-border-color: var(--stroke-300) !important;
            --bs-modal-border-color: var(--stroke-300) !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="favoritesModalLabel">
                    <?php echo t("favorites_modal_title") ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="favorites-list">
                    <!-- Favorite messages will be loaded here -->
                    <div class="text-center py-3" id="favorites-loading" style="display:none;">
                        <span class="spinner-border spinner-border-sm"></span>
                    </div>
                </div>
                <div id="favorites-empty" class="text-center py-3" style="display:none;">
                    <?php echo t('favorites_modal_no_favorites'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function removeFavoriteClickHandler(messageId) {
        fetch('/api/favorites', {
            method: 'DELETE',
            body: new URLSearchParams({message_id: messageId})
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remove the item from the modal
                    const item = document.querySelector(`.favorite-message[data-message-id="${messageId}"]`);
                    if (item) item.remove();
                    const wrapper = document.querySelector(`.chat__message__wrapper[data-message-id="${messageId}"]`);
                    if (wrapper) {
                        const starBtn = wrapper.querySelector('.star-btn');
                        starBtn.classList.remove('favorite');
                    }
                } else {
                    alert(data.message || 'Failed to remove from favorites.');
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const favoritesList = document.getElementById('favorites-list');
        const favoritesEmpty = document.getElementById('favorites-empty');
        const favoritesLoading = document.getElementById('favorites-loading');
        const favoritesModal = document.getElementById('favoritesModal');

        function renderFavorite(msg) {
            const div = document.createElement('div');
            div.className = 'favorite-message mb-3 p-2 border rounded';
            div.style.backgroundColor = 'var(--bg-300) !important';
            div.style.color = 'var(--text-50) !important';
            div.style.borderColor = 'var(--stroke-300) !important';
            div.dataset.messageId = msg.id;
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-end gap-3">
                    <div>
                        <span>${msg.message.replace(/\n/g, '<br>')}</span>
                    </div>
                    <button class="btn btn-danger" title="Remove from Favorites" onclick="removeFavoriteClickHandler('${msg.id}')">
                        <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
            `;
            return div;
        }

        async function loadFavorites() {
            favoritesList.innerHTML = '';
            favoritesLoading.style.display = 'block';
            favoritesEmpty.style.display = 'none';
            try {
                const res = await fetch('/api/favorites');
                const data = await res.json();
                favoritesLoading.style.display = 'none';
                if (data.success && data.data.length) {
                    data.data.forEach(msg => {
                        favoritesList.appendChild(renderFavorite(msg));
                    });
                } else {
                    favoritesEmpty.style.display = 'block';
                }
            } catch (e) {
                favoritesLoading.style.display = 'none';
                favoritesEmpty.style.display = 'block';
            }

            feather.replace();
        }

        favoritesModal.addEventListener('show.bs.modal', loadFavorites);
    });
</script>
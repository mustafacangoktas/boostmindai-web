<div class="sidebar-overlay d-md-none"></div>
<div class="sidebar">
    <button class="sidebar-close d-md-none">
        <i data-feather="x" style="width: 24px; height: 24px;"></i>
    </button>
    <h3 class="fs-5">
        <?php echo t("chat_history_title") ?>
    </h3>
    <nav class="sidebar__nav" style="overflow-y: auto; height: 60vh;">
        <ul class="list-unstyled" id="chat-history-list">
            <!-- Chat items will be loaded here -->
        </ul>
        <div id="sidebar-loading" class="text-center py-2" style="display:none;">
            <span class="spinner-border spinner-border-sm"></span>
        </div>
    </nav>
    <div class="d-flex align-items-center justify-content-start gap-2 favorites__button">
        <div class="d-flex align-items-center justify-content-center rounded-circle border border-2 border-white"
             style="width: 38px; height: 38px;">
            <i data-feather="star" style="stroke-width: 2.5; width: 22px; height: 22px;"></i>
        </div>
        <div data-bs-toggle="modal" data-bs-target="#favoritesModal" style="cursor:pointer;">
            <h3 style="font-size: 1rem;" class="m-0 mb-1">
                <?php echo t("favorites_modal_title") ?>
            </h3>
            <p class="text-200 m-0" style="font-size: 0.8rem;">
                <?php echo t("favorites_button_description") ?>
            </p>
        </div>
    </div>
</div>
<script>
    const chatHistoryList = document.getElementById('chat-history-list');
    const sidebarNav = document.querySelector('.sidebar__nav');
    const sidebarLoading = document.getElementById('sidebar-loading');
    let sidebarLoadingChats = false;
    let sidebarAllLoaded = false;
    let sidebarLastDate = null;
    var chatId = '<?php echo isset($_GET['id']) ? addslashes($_GET['id']) : ''; ?>';

    function formatChatDate(dateStr) {
        const date = new Date(dateStr);
        // Format as "Month Day" (e.g., May 1)
        return date.toLocaleDateString(undefined, {month: 'long', day: 'numeric'});
    }

    function getRelativeDateDesc(dateStr) {
        const today = new Date();
        const date = new Date(dateStr);
        // Remove time part for accurate day comparison
        today.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);

        const diffTime = today - date;
        const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays === 0) {
            return '<?php echo t("chat_today") ?>';
        } else if (diffDays === 1) {
            return '1 <?php echo t("day_ago") ?>';
        } else if (diffDays > 1) {
            return `${diffDays} <?php echo t("days_ago") ?>`;
        } else {
            return '';
        }
    }

    function renderChatItem(date, isActive) {
        const li = document.createElement('li');
        li.className = 'chat-history-item sidebar__chat-item' + (isActive ? ' active' : '');
        li.innerHTML = `
        <div class="d-flex align-items-center sidebar__chat-link">
            <a href="/chat/${encodeURIComponent(date)}" class="flex-grow-1 text-decoration-none">
                <div class="sidebar__chat-title">${
            getRelativeDateDesc(date) === '<?php echo t("chat_today") ?>' ?
                '<?php echo t("chat_today") ?>, ' :
                getRelativeDateDesc(date) === '1 day ago' ?
                    '<?php echo t("chat_yesterday") ?>, ' : ""
        }${formatChatDate(date)}</div>
                <div class="sidebar__chat-desc text-300">${getRelativeDateDesc(date)}</div>
            </a>
            <div class="sidebar__chat-actions">
                <div class="dropdown">
                    <button class="btn btn-link p-0 sidebar__chat-menu" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i data-feather="more-horizontal"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li class="d-flex align-items-center dropdown-item gap-1 cursor-pointer delete-chat-btn" onclick="deleteChat('${date}')">
                            <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
                            <span>
                                <?php echo t("chat_delete_button") ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    `;
        return li;
    }

    async function loadSidebarChats() {
        if (sidebarLoadingChats || sidebarAllLoaded) return;
        sidebarLoadingChats = true;
        sidebarLoading.style.display = 'block';
        let url = '/api/chats?limit=10';
        if (sidebarLastDate) url += `&before=${encodeURIComponent(sidebarLastDate)}`;
        const res = await fetch(url);
        const data = await res.json();
        if (!data.success || !data.dates.length) {
            sidebarAllLoaded = true;
            sidebarLoading.style.display = 'none';
            sidebarLoadingChats = false;
            return;
        }

        // After fetching data from the API
        const today = new Date();
        const todayStr = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');

        let hasToday = data.dates.includes(todayStr);

        // If today's chat is missing, add it at the top
        if (!hasToday) {
            data.dates.unshift(todayStr);
        }

        // Render chat items
        for (const date of data.dates) {
            const isActive = chatId ? (chatId === date) : (date === todayStr);
            chatHistoryList.appendChild(renderChatItem(date, isActive));
            sidebarLastDate = date;
        }

        feather.replace();
        sidebarLoading.style.display = 'none';
        sidebarLoadingChats = false;
    }

    function deleteChat(chatDate) {
        if (!confirm('<?php echo t("chat_delete_confirmation") ?>')) return;
        const today = new Date();
        const todayStr = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');

        const formData = new FormData();
        formData.append('chat_date', chatDate);
        fetch('/api/chat/delete', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (chatDate === todayStr) {
                        // If deleting today's chat, redirect to the chat page
                        window.location.href = '/chat';
                    } else if (chatDate === "<?php echo $_GET['id'] ?? null; ?>") {
                        // If deleting the current chat, redirect to today's chat
                        window.location.href = '/chat';
                    }
                    // Remove the chat item from the sidebar
                    const chatItem = document.querySelector(`.sidebar__chat-item a[href="/chat/${encodeURIComponent(chatDate)}"]`);
                    if (chatItem) {
                        chatItem.closest('.sidebar__chat-item').remove();
                    }
                } else {
                    alert(data.message || '<?php echo t("chat_delete_error") ?>');
                }
            })
            .catch(err => {
                console.error(err);
                alert('<?php echo t("chat_delete_error") ?>');
            });
    }

    sidebarNav.addEventListener('scroll', () => {
        if (
            sidebarNav.scrollTop + sidebarNav.clientHeight >= sidebarNav.scrollHeight - 50 &&
            !sidebarLoadingChats && !sidebarAllLoaded
        ) {
            loadSidebarChats();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        loadSidebarChats();
    });
</script>
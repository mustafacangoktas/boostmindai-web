<section class="chat__messages scrollbar">
</section>
<script>
    const chatSection = document.querySelector('.chat__messages');
    let loading = false;
    let allLoaded = false;
    let earliestTimestamp = null;
    var chatId = '<?php echo isset($_GET['id']) ? addslashes($_GET['id']) : ''; ?>';

    function renderMessage(msg) {
        // Simple rendering, adapt as needed
        const wrapper = document.createElement('div');
        wrapper.className = 'chat__message__wrapper chat__message__wrapper--' + (msg.role === 'user' ? 'user' : 'assistant');
        wrapper.dataset.messageId = msg.id;
        wrapper.innerHTML = `
        <div class="chat__message">
            <div class="chat__message__content">
                <p class="m-0">${msg.role === 'assistant' ? msg.message.replace(/\n/g, '<br>') : msg.message}</p>
            </div>
        </div>
    `;
        if (msg.role === 'assistant') {
            const isFavorite = msg.is_favorite === 1 || msg.is_favorite === true;

            wrapper.innerHTML += `
        <div class="message-actions">
            <button class="btn btn-link p-1 message-action-btn copy-btn" title="Copy" onclick="copyMessageClickHandler()">
                <i data-feather="copy" style="width: 18px; height: 18px;"></i>
            </button>
            <button class="btn btn-link p-1 message-action-btn star-btn${isFavorite ? ' favorite' : ''}" title="Add to Favorites" onclick="toggleFavoriteMessageClickHandler()">
                <i data-feather="star" style="width: 18px; height: 18px;"></i>
            </button>
            <button <?php if (isset($_GET['id'])) echo 'style="display: none;"';?> class="btn btn-link p-1 message-action-btn regenerate-btn" title="Regenerate" onclick="regenerateMessageClickHandler()">
                <i data-feather="refresh-cw" style="width: 18px; height: 18px;"></i>
            </button>
        </div>`;
        }
        return wrapper;
    }

    async function loadMessages(before = null) {
        if (loading || allLoaded) return;
        loading = true;
        let url = chatId ? `/api/chat?id=${encodeURIComponent(chatId)}&limit=10` : '/api/chat?limit=10';
        if (before) url += `&before=${encodeURIComponent(before)}`;
        const res = await fetch(url);
        const data = await res.json();
        if (!data.success || !data.data.length) {
            allLoaded = true;
            loading = false;
            return;
        }

        document.getElementById('quick_reply_row').style.setProperty('display', 'none', 'important');

        // Insert at top
        const scrollPos = chatSection.scrollHeight - chatSection.scrollTop;
        for (const msg of data.data) {
            const messageEl = renderMessage(msg);
            chatSection.insertBefore(messageEl, chatSection.firstChild);
            if (!earliestTimestamp || new Date(msg.created_at) < new Date(earliestTimestamp)) {
                earliestTimestamp = msg.created_at;
            }
        }
        // Maintain scroll position
        chatSection.scrollTop = chatSection.scrollHeight - scrollPos;
        feather.replace();
        loading = false;
    }

    chatSection.addEventListener('scroll', () => {
        if (chatSection.scrollTop < 100 && !loading && !allLoaded) {
            loadMessages(earliestTimestamp);
        }
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        loadMessages();
    });

    function copyMessageClickHandler() {
        const e = window.event;
        e.preventDefault();
        const btn = e.currentTarget;
        // Find the message text
        const messageContent = btn.closest('.chat__message__wrapper')
            .querySelector('.chat__message__content p');
        if (messageContent) {
            // Remove <br> tags for copying plain text
            const text = messageContent.innerText;
            navigator.clipboard.writeText(text).then(function () {
                btn.innerHTML = '<i data-feather="check" style="width: 18px; height: 18px;"></i>';
                feather.replace();
                setTimeout(() => {
                    btn.innerHTML = '<i data-feather="copy" style="width: 18px; height: 18px;"></i>';
                    feather.replace();
                }, 1000);
            });
        }
    }

    async function regenerateMessageClickHandler() {
        const e = window.event;
        e.preventDefault();
        const btn = e.target;
        const wrapper = btn.closest('.chat__message__wrapper');
        // Find the message ID (you need to render it in the DOM)
        const messageId = wrapper.dataset.messageId;
        if (!messageId) return;

        btn.disabled = true;
        btn.innerHTML = '<i data-feather="loader" class="spin"></i>';
        feather.replace();

        try {
            const formData = new FormData();
            formData.append('message_id', messageId);
            const res = await fetch('/api/chat/regenerate', {
                method: 'POST',
                body: formData,
            });
            const data = await res.json();
            if (data.success) {
                // Update the message content
                wrapper.querySelector('.chat__message__content p').innerHTML = data.message.replace(/\n/g, '<br>');
            } else {
                alert(data.message || 'Failed to regenerate message.');
            }
        } catch (err) {
            alert('Network error.');
        }
        btn.disabled = false;
        btn.innerHTML = '<i data-feather="refresh-cw" style="width: 18px; height: 18px;"></i>';
        feather.replace();
    }

    async function toggleFavoriteMessageClickHandler() {
        const e = window.event;
        const wrapper = e.target.closest('.chat__message__wrapper');
        const messageId = wrapper.dataset.messageId;
        const starBtn = wrapper.querySelector('.star-btn');
        const isFavorited = starBtn.classList.toggle('favorite');
        if (isFavorited) {
            const res = await fetch('/api/favorites', {
                method: 'POST',
                body: new URLSearchParams({message_id: messageId})
            });
            const data = await res.json();
            if (!data.success && data.message) {
                alert(data.message);
            }
        } else {
            await fetch('/api/favorites', {
                method: 'DELETE',
                body: new URLSearchParams({message_id: messageId})
            });
        }
    }
</script>

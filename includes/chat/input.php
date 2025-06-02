<form action="">
    <div class="d-flex flex-row align-items-center justify-content-md-center gap-3 mt-4"
         style="overflow-x: auto; padding: 0 1rem;" id="quick_reply_row">
        <button type="button" class="btn quick-reply-button">
            <?php echo t("quick_reply_stressed") ?>
        </button>
        <button type="button" class="btn quick-reply-button">
            <?php echo t("quick_reply_anxious") ?>
        </button>
    </div>
    <div class="chat__input__wrapper d-flex align-items-end gap-3 mt-4">
        <label class="d-flex flex-grow-1">
            <textarea class="form-control chat-textarea message-input scrollbar"
                      placeholder="<?php echo t('chat_input_placeholder') ?>"
                      rows="1" <?php echo $_GET['id'] ? 'disabled' : '' ?>
            ></textarea>
        </label>
        <button type="submit" class="btn btn-primary send-button" <?php echo $_GET['id'] ? 'disabled' : '' ?>>
            <i data-feather="send"
               style="width: 18px; height: 18px; margin-right: 6px; vertical-align: -3px;"></i>
            <span class="d-none d-md-inline">
                <?php echo t("chat_send_button") ?>
            </span>
        </button>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const textarea = form.querySelector('.message-input');
        const sendButton = form.querySelector('.send-button');
        const chatSection = document.querySelector('.chat__messages');
        const quickReplies = form.querySelectorAll('.quick-reply-button');

        function renderMessage(msg, isThinking = false) {
            const wrapper = document.createElement('div');
            wrapper.className = 'chat__message__wrapper chat__message__wrapper--' + (msg.role === 'user' ? 'user' : 'assistant');
            wrapper.dataset.messageId = msg.id || '';
            wrapper.innerHTML = `
            <div class="chat__message">
                <div class="chat__message__content">
                    <p class="m-0">${isThinking ? '<em><?php echo t("chat_thinking") ?></em>' : (msg.role === 'assistant' ? msg.message.replace(/\n/g, '<br>') : msg.message)}</p>
                </div>
            </div>
        `;

            if (msg.role === 'assistant') {
                wrapper.innerHTML += `
                <div class="message-actions">
                    <button class="btn btn-link p-1 message-action-btn copy-btn" title="Copy" onclick="copyMessageClickHandler()">
                        <i data-feather="copy" style="width: 18px; height: 18px;"></i>
                    </button>
                    <button class="btn btn-link p-1 message-action-btn star-btn" title="Add to Favorites">
                        <i data-feather="star" style="width: 18px; height: 18px;"></i>
                    </button>
                    <button <?php if (isset($_GET['id'])) echo 'style="display: none;"';?> class="btn btn-link p-1 message-action-btn regenerate-btn" title="Regenerate" onclick="regenerateMessageClickHandler()">
                        <i data-feather="refresh-cw" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>`;
            }
            return wrapper;
        }

        async function sendMessage(e, quickReplyText = null) {
            if (e) e.preventDefault();
            const message = quickReplyText !== null ? quickReplyText : textarea.value.trim();
            if (!message) return;

            // Render user message
            const userMsg = {role: 'user', message};
            const userEl = renderMessage(userMsg);
            chatSection.appendChild(userEl);

            // Render assistant "thinking" message
            const thinkingMsg = {role: 'assistant', message: ''};
            const thinkingEl = renderMessage(thinkingMsg, true);
            chatSection.appendChild(thinkingEl);

            // Scroll to bottom
            chatSection.scrollTop = chatSection.scrollHeight;

            textarea.value = '';
            textarea.disabled = true;
            sendButton.disabled = true;

            document.getElementById('quick_reply_row').style.setProperty('display', 'none', 'important');
            try {
                const formData = new FormData();
                formData.append('message', message);
                const res = await fetch('/api/chat', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success && data.message) {
                    thinkingEl.querySelector('p').innerHTML = data.message.replace(/\n/g, '<br>');
                } else {
                    thinkingEl.querySelector('p').innerHTML = '<span style="color:red"><?php echo t("chat_error_fallback") ?></span>';
                }
            } catch (err) {
                thinkingEl.querySelector('p').innerHTML = '<span style="color:red"><?php echo t("chat_error_fallback") ?></span>';
            } finally {
                textarea.disabled = false;
                sendButton.disabled = false;
                textarea.focus();
                chatSection.scrollTop = chatSection.scrollHeight;
                feather.replace();
            }
        }

        // Submit on send button or Enter (without Shift)
        form.addEventListener('submit', sendMessage);
        textarea.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage(e);
            }
        });

        // Quick reply click handler
        quickReplies.forEach(btn => {
            btn.addEventListener('click', function () {
                sendMessage(null, btn.textContent.trim());
            });
        });
    });
</script>

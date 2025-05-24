<form action="">
    <div class="d-flex flex-row align-items-center justify-content-md-center gap-3 my-4"
         style="overflow-x: auto; padding: 0 1rem;">
        <button type="button" class="btn quick-reply-button">I'm feeling stressed</button>
        <button type="button" class="btn quick-reply-button">I'm feeling uneasy</button>
        <button type="button" class="btn quick-reply-button">I'm feeling lonely</button>
    </div>
    <div class="chat__input__wrapper d-flex align-items-end gap-3">
        <label class="d-flex flex-grow-1">
            <textarea class="form-control chat-textarea message-input" placeholder="Type your message..."
                      rows="1"></textarea>
        </label>
        <button type="submit" class="btn btn-primary send-button">
            <i data-feather="send"
               style="width: 18px; height: 18px; margin-right: 6px; vertical-align: -3px;"></i>
            <span class="d-none d-md-inline">Send</span>
        </button>
    </div>
</form>


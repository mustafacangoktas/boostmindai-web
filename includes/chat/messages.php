<?php
function renderUserMessage($text): void
{
    echo '<div class="chat__message__wrapper chat__message__wrapper--user">';
    echo '    <div class="chat__message">';
    echo '        <div class="chat__message__content">';
    echo '            <p class="m-0">' . htmlspecialchars($text) . '</p>';
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
}

function renderAssistantMessage($text): void
{
    echo '<div class="chat__message__wrapper chat__message__wrapper--assistant">';
    echo '    <div class="chat__message">';
    echo '        <div class="chat__message__content">';
    echo '            <p class="m-0">' . nl2br(htmlspecialchars($text)) . '</p>';
    echo '        </div>';
    echo '    </div>';
    echo '    <div class="message-actions">';
    echo '        <button class="btn btn-link p-1 message-action-btn copy-btn" title="Copy">';
    echo '            <i data-feather="copy" style="width: 18px; height: 18px;"></i>';
    echo '        </button>';
    echo '        <button class="btn btn-link p-1 message-action-btn star-btn" title="Add to Favorites">';
    echo '            <i data-feather="star" style="width: 18px; height: 18px;"></i>';
    echo '        </button>';
    echo '        <button class="btn btn-link p-1 message-action-btn regenerate-btn" title="Regenerate">';
    echo '            <i data-feather="refresh-cw" style="width: 18px; height: 18px;"></i>';
    echo '        </button>';
    echo '    </div>';
    echo '</div>';
}

?>
<section class="chat__messages">
    <?php
    renderUserMessage("I'm feeling a bit tired today.");
    renderAssistantMessage("I'm sorry to hear you're not feeling great. It's perfectly normal to feel down sometimes.\n\nRemember, you're not alone and it's okay to experience your feelings. If you need more support, consider talking to someone you trust or seeking professional help.\n\nWishing you better days ahead.");
    ?>
</section>


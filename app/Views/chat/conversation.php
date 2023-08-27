<div class="chat-widget-info" data-id="<?= $conversation['id'] ?>">
    <div class="icon" <?= 'style="background-color: ' . color_from_string($conversation['account_sender_id'] != $_SESSION['id'] ? $conversation['account_sender_full_name'] : $conversation['account_receiver_full_name']) . '"' ?>><?= substr($conversation['account_sender_id'] != $_SESSION['id'] ? $conversation['account_sender_full_name'] : $conversation['account_receiver_full_name'], 0, 1) ?></div>
    <div class="details">
        <div class="title"><?= htmlspecialchars($conversation['account_sender_id'] != $_SESSION['id'] ? $conversation['account_sender_username'] : $conversation['account_receiver_username'], ENT_QUOTES) ?></div>
        <div class="msg"><?= $conversation['status'] == 'Online' ? 'Online' : $conversation['last_seen'] ?></div>
    </div>
</div>
<div class="chat-widget-messages">
    <!-- <p class="date">Anda memulai obrolan dengan <?= htmlspecialchars($_SESSION['id'] == $conversation['account_sender_id'] ? $conversation['account_receiver_full_name'] : $conversation['account_sender_full_name'], ENT_QUOTES) ?></p> -->
    <?php foreach ($messages as $date => $array) : ?>
        <p class="date"><?= $date == date('y/m/d') ? 'Today' : $date ?></p>
        <?php foreach ($array as $message) : ?>
            <!-- <div class="chat-widget-message<?= $_SESSION['id'] == $message['account_id'] ? '' : ' alt' ?>" title="<?= date('H:i\p\m', strtotime($message['submit_date'])) ?>"><?= htmlspecialchars($message['msg'], ENT_QUOTES) ?> <small style="margin-left:20px;"><?= date('H:i\p\m', strtotime($message['submit_date'])) ?></small></div> -->
            <div class="chat-widget-message<?= $_SESSION['id'] == $message['account_id'] ? '' : ' alt' ?>" title="<?= date('H:i\p\m', strtotime($message['submit_date'])) ?>"><?= $message['msg'] ?> <small style="margin-left:20px;"><?= date('H:i\p\m', strtotime($message['submit_date'])) ?></small></div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <a href="#"></a>
</div>
<form onsubmit="event.preventDefault();" action="<?= base_url('chat/post_message'); ?>" method="post" class="chat-widget-input-message" autocomplete="off">
    <span class="dropup">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="dropdown" title="Attachment">
            <i class="fas fa-paperclip"></i>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#" onclick="$('#att-image').click();"><i class="fas fa-image"></i>&emsp;Foto</a>
            <a class="dropdown-item" href="#" onclick="$('#att-file').click();"><i class="fas fa-file"></i>&nbsp;&emsp;Dokumen</a>
        </div>
    </span>
    <button type="button" title="Emoji" class="btn btn-sm btn-outline-secondary emoji-button empty" onclick="
            const picker = picmoPopup.createPopup({},{referenceElement: this,triggerElement: this});
            picker.toggle();
            picker.addEventListener('emoji:select', event => {
                $('input[name=msg]').val($('input[name=msg]').val() + event.emoji);
            });
        ">
        <i class="far fa-smile"></i>
    </button>
    <input type="text" name="msg" placeholder="Message">
    <input type="hidden" name="id" value="<?= $conversation['id'] ?>">
    <input type="file" onchange="
            let chatWidgetInputMsg = document.querySelector('.chat-widget-input-message');
            let chatWidgetMsg = document.createElement('div');
            chatWidgetMsg.classList.add('chat-widget-message');
            chatWidgetMsg.textContent = 'Sending Image...';
            document.querySelector('.chat-widget-messages').insertAdjacentElement('beforeend', chatWidgetMsg);
            fetch(chatWidgetInputMsg.action, { 
                cache: 'no-store',
                method: 'POST',
                body: new FormData(chatWidgetInputMsg)
            });
            this.value='';
        " id="att-image" name="file_img" style="display:none;" accept="image/png, image/gif, image/jpeg">
    <input type="file" onchange="
            let chatWidgetInputMsg = document.querySelector('.chat-widget-input-message');
            let chatWidgetMsg = document.createElement('div');
            chatWidgetMsg.classList.add('chat-widget-message');
            chatWidgetMsg.textContent = 'Sending Photo...';
            document.querySelector('.chat-widget-messages').insertAdjacentElement('beforeend', chatWidgetMsg);
            fetch(chatWidgetInputMsg.action, { 
                cache: 'no-store',
                method: 'POST',
                body: new FormData(chatWidgetInputMsg)
            });
            this.value='';
        " id="att-file" name="file_doc" style="display:none;" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*">
</form>

<script>
    function a() {
        alert('aaaa');
    }
</script>
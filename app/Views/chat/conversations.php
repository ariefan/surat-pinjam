<div class="chat-widget-info">
    <div class="input-group pl-2">
        <div class="input-group-prepend">
            <div class="input-group-text" style="background-color:#fff;"><i class="fas fa-search"></i></div>
        </div>
        <input id="chat-search" type="text" class="form-control" style="background-color:#fff;border-left:none;" placeholder="Cari">
    </div>
</div>

<div class="chat-widget-conversations">
    <a href="#" class="chat-widget-new-conversation" style="display:none;" data-id="">&plus; New Chat</a>
    <?php foreach ($conversations as $conversation) : ?>
        <a href="#" class="chat-widget-conversation" data-id="<?= $conversation['id'] ?>">
            <div class="icon" <?= 'style="background-color: ' . color_from_string($conversation['sender_id'] != $_SESSION['id'] ? $conversation['sender_name'] : $conversation['receiver_name']) . '"' ?>><?= substr($conversation['sender_id'] != $_SESSION['id'] ? $conversation['sender_name'] : $conversation['receiver_name'], 0, 1) ?></div>
            <div class="details">
                <?php 
                $msg = $conversation['msg'];
                $msg = str_contains($msg ?: '', 'href="') ? 'Document' : $msg;
                $msg = str_contains($msg ?: '', '<img') ? 'Photo' : $msg;
                ?>
                <div class="title"><?= $conversation['status'] == 'Online' ? ' <i class="fa-solid fa-circle nav-icon" style="color:#00aa00;font-size:75%;"></i>' : ''; ?> <?= htmlspecialchars($conversation['sender_id'] != $_SESSION['id'] ? $conversation['sender_username'] : $conversation['receiver_username'], ENT_QUOTES) ?></div>
                <div class="msg"><?= htmlspecialchars($msg ?? '', ENT_QUOTES) ?></div>
            </div>
            <div class="date">
                <?php if ($conversation['msg_date']) : ?>
                    <?= date('Y/m/d') == date('Y/m/d', strtotime($conversation['msg_date'])) ? date('H:i', strtotime($conversation['msg_date'])) : date('d/m/y', strtotime($conversation['msg_date'])) ?>
                <?php else : ?>
                    <?= date('Y/m/d') == date('Y/m/d', strtotime($conversation['submit_date'])) ? date('H:i', strtotime($conversation['submit_date'])) : date('d/m/y', strtotime($conversation['submit_date'])) ?>
                <?php endif; ?>
                <?php if ((int)$conversation['msg_unread'] > 0) : ?>
                    <br /><span class="badge badge-success" style="font-size:11px;"><?= $conversation['msg_unread']; ?></b></span>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>
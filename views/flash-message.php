<?php if($message): ?>
    <div class="alert alert-<?= $message['type']?>" role="alert">
        <?= $message['text'] ?>
    </div>
<?php endif; ?>


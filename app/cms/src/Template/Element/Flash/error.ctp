<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message error" onclick="this.classList.add('hidden');"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?= $message ?></div>

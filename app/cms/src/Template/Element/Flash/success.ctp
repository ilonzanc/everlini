<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message success" onclick="this.classList.add('hidden')"><i class="fa fa-info-circle" aria-hidden="true"></i> <?= $message ?></div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attachment[]|\Cake\Collection\CollectionInterface $attachments
 */
?>
<div class="attachments index large-9 medium-8 columns content">
    <h2><?= __('Attachments Overview') ?></h2>

        <?php foreach ($attachments as $attachment): ?>
            <?php echo $this->Html->link('<div class="gallery-image" style="background-image: url(' . "'" . $attachment->path . "'" . ')"></div>', ['action' => 'view', $attachment->id],['escape'=>false]); ?>
        <?php endforeach; ?>

    <?php echo $this->element('pagination', []);?>
</div>

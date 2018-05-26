<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attachment $attachment
 */
?>
<div class="attachments form large-9 medium-8 columns content">
    <?= $this->Form->create($attachment, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Add Attachment') ?></legend>
        <?php
            echo $this->Form->file('submittedfile');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

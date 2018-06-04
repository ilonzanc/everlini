<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Organisation $organisation
 */
?>
<div class="organisations form large-9 medium-8 columns content">
    <?= $this->Form->create($organisation) ?>
    <h2><?= __('Add Organisation') ?></h2>
    <?php
        echo $this->Form->control('role_id', ['type' => 'text', 'value' => 3]);
        echo $this->Form->control('name');
        echo $this->Form->control('description');
        echo $this->Form->control('email');
        echo $this->Form->control('password');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
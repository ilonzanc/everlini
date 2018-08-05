<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin $admin
 */
?>
<div class="admins form large-9 medium-8 columns content">
    <?= $this->Form->create($admin) ?>
        <h2><?= __('Add Admin') ?></h2>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('username');
            echo $this->Form->control('organisations._ids', ['options' => $organisations]);
        ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

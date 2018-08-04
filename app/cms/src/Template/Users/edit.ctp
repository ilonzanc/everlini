<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-9 medium-8 columns content">
<?= $this->Form->create($user) ?>
    <h2><?= __('Edit User') ?></h2>
    <?php
        echo $this->Form->control('role_id', ['options' => $roles]);
        echo $this->Form->control('username');
        echo $this->Form->control('email');
        echo $this->Form->control('password');
    ?>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profile $profile
 */
?>
<div class="profiles form large-9 medium-8 columns content">
    <?= $this->Form->create($profile) ?>
    <h2><?= __('Add Profile') ?></h2>
    <?php
        echo $this->Form->control('role_id', ['type' => 'text', 'value' => 2]);
        echo $this->Form->control('email');
        echo $this->Form->control('password');

        echo $this->Form->control('firstname');
        echo $this->Form->control('lastname');
        echo $this->Form->control('dateofbirth', ['empty' => true]);
        echo $this->Form->control('streetname');
        echo $this->Form->control('housenr');
        echo $this->Form->control('city');
        echo $this->Form->control('country');
        echo $this->Form->control('organisation');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

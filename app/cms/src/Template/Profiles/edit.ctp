<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profile $profile
 */
?>
<div class="profiles form large-9 medium-8 columns content">
    <?php $dateofbirth = $this->Time->format(
        $profile->dateofbirth, #Your datetime variable
        'YYYY-MM-dd'
        );?>
    <?= $this->Form->create($profile) ?>
    <h2><?= __('Edit Profile') ?></h2>
    <?php
        echo $this->Form->control('user_id', ['options' => $users, 'type' => 'hidden']);
        echo $this->Form->control('firstname');
        echo $this->Form->control('lastname');
        echo $this->Form->label('dateofbirth');
        echo $this->Form->text('dateofbirth', array(
            'type' => 'date',
            'value' => $dateofbirth
        ));
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

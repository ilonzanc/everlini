<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event $event
 */
?>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event) ?>
    <fieldset>
        <legend><?= __('Add Event') ?></legend>
        <?php
            echo $this->Form->control('profile_id', ['options' => $profiles, 'empty' => true]);
            echo $this->Form->control('venue_id', ['options' => $venues]);
            echo $this->Form->control('name');
            echo $this->Form->control('description');
            echo $this->Form->control('startdate', ['empty' => true]);
            echo $this->Form->control('enddate', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

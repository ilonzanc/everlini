<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event $event
 */
$today = date("Y");
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $event->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]
            )
        ?></li>
    </ul>
</nav>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Edit Event') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
            echo $this->Form->control('name');
        echo $this->Form->control('description');
        echo $this->Form->input( 'startdate', [
            'templates' => ['dateWidget' => '{{day}}{{month}}{{year}}'],
            'label' => 'Event Start Date',
            'minYear' => $today,
            'maxYear' => $today + 10,
            'orderYear' => 'asc',
            'empty' => [
                'year' => 'Choose year...',
                'month' => 'Choose month...',
                'day' => 'Choose day...'
            ]
        ]);
        echo $this->Form->text('starttime', [
            'value' => '00:00',
            'placeholder' => '00:00'
        ]);
        echo $this->Form->input( 'enddate', [
            'templates' => ['dateWidget' => '{{day}}{{month}}{{year}}'],
            'label' => 'Event End Date',
            'minYear' => $today,
            'maxYear' => $today + 10,
            'orderYear' => 'asc',
            'empty' => [
                'year' => 'Choose year...',
                'month' => 'Choose month...',
                'day' => 'Choose day...'
            ]
        ]);
        echo $this->Form->text('endtime', [
            'value' => '00:00',
            'placeholder' => '00:00'
        ]);
        echo $this->Form->control('street');
        echo $this->Form->control('housenr');
        echo $this->Form->control('city');
        echo $this->Form->control('postal_code');
        echo $this->Form->control('country');
        echo $this->Form->file('submittedfile');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

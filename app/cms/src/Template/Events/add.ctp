<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event $event
 */

$today = date("Y");
?>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event) ?>
    <h3><?= __('Add Event') ?></h3>
    <?php
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
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

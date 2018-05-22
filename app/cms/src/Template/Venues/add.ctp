<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Venue $venue
 */
?>
<div class="venues form large-9 medium-8 columns content">
    <?= $this->Form->create($venue) ?>
    <h2><?= __('Add New Venue') ?></h2>
    <?php
        echo $this->Form->control('name');
        echo $this->Form->control('streetname');
        echo $this->Form->control('housenr');
        echo $this->Form->control('city');
        echo $this->Form->control('country');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Interest $interest
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Interests'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="interests form large-9 medium-8 columns content">
    <?= $this->Form->create($interest) ?>
    <fieldset>
        <legend><?= __('Add Interest') ?></legend>
        <?php
            echo $this->Form->control('title');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

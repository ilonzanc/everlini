<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Organisation $organisation
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $organisation->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $organisation->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Organisations'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="organisations form large-9 medium-8 columns content">
    <?= $this->Form->create($organisation) ?>
    <fieldset>
        <legend><?= __('Edit Organisation') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

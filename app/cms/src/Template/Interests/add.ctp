<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Interest $interest
 */
?>
<div class="interests form large-9 medium-8 columns content">
    <?= $this->Form->create($interest) ?>
    <h2><?= __('Add Interest') ?></h2>
    <?php
        echo $this->Form->control('name');
        echo $this->Form->input('parent_id', ['options' => $list, 'empty' => 'No parent category']);
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Interest $interest
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Interest'), ['action' => 'edit', $interest->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Interest'), ['action' => 'delete', $interest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $interest->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Interests'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Interest'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="interests view large-9 medium-8 columns content">
    <h3><?= h($interest->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($interest->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($interest->id) ?></td>
        </tr>
    </table>
</div>

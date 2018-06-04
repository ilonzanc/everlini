<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Interest[]|\Cake\Collection\CollectionInterface $interests
 */
?>
<div class="interests index large-9 medium-8 columns content">
    <h2><?= __('Interests Overview') ?></h2>
    <section class="header__createbtn" id="actions-sidebar">
        <?=
            $this->Html->link('<i class="fa fa-plus"></i> New interest', ['action' => 'add'],['escape'=>false,'class'=>'btn small-btn']);
        ?>
    </section>
    <table class="overview-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($interests as $interest): ?>
            <tr>
                <td><?= $this->Number->format($interest->id) ?></td>
                <td><?= h($interest->title) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $interest->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $interest->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $interest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $interest->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination', []);?>
</div>

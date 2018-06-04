<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Organisation[]|\Cake\Collection\CollectionInterface $organisations
 */
?>
<div class="organisations index large-9 medium-8 columns content">
<h2><?= __('Organisations Overview') ?></h2>
    <section class="header__createbtn" id="actions-sidebar">
        <?=
            $this->Html->link('<i class="fa fa-plus"></i> New organisation', ['action' => 'add'],['escape'=>false,'class'=>'btn small-btn']);
        ?>
    </section>
    <table class="overview-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($organisations as $organisation): ?>
            <tr>
                <td><?= $this->Number->format($organisation->id) ?></td>
                <td><?= h($organisation->name) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $organisation->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $organisation->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $organisation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $organisation->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination', []);?>
</div>

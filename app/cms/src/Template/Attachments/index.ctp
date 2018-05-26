<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Attachment[]|\Cake\Collection\CollectionInterface $attachments
 */
?>
<div class="attachments index large-9 medium-8 columns content">
    <h2><?= __('Attachments Overview') ?></h2>
    <section class="header__createbtn" id="actions-sidebar">
        <?=
            $this->Html->link('<i class="fa fa-plus"></i> New event', ['action' => 'add'],['escape'=>false,'class'=>'btn small-btn']);
        ?>
    </section>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('path') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attachments as $attachment): ?>
            <tr>
                <td><?= $this->Number->format($attachment->id) ?></td>
                <td><?= h($attachment->name) ?></td>
                <td><?= h($attachment->path) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $attachment->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $attachment->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $attachment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attachment->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination', []);?>
</div>

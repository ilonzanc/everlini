<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profile[]|\Cake\Collection\CollectionInterface $profiles
 */
?>
<div class="profiles index large-9 medium-8 columns content">
    <h2><?= __('Profiles Overview') ?></h2>
    <section class="header__createbtn" id="actions-sidebar">
        <?=
            $this->Html->link('<i class="fa fa-plus"></i> New profile', ['action' => 'add'],['escape'=>false,'class'=>'btn small-btn']);
        ?>
    </section>
    <table class="overview-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('First name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Last name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Organisation') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profiles as $profile): ?>
            <tr>
                <td><?= $this->Number->format($profile->id) ?></td>
                <td><?= h($profile->firstname) ?></td>
                <td><?= h($profile->lastname) ?></td>
                <td><?= h($profile->organisation) ?></td>
                <td><?= h($profile->created) ?></td>
                <td><?= h($profile->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link('<i class="fa fa-eye"></i>', ['action' => 'view', $profile->id], ['escape'=>false]) ?>
                    <?= $this->Html->link('<i class="fa fa-pencil"></i>', ['action' => 'edit', $profile->id], ['escape'=>false]) ?>
                    <?= $this->Form->postLink('<i class="fa fa-trash"></i>', ['action' => 'delete', $profile->id], ['escape'=>false], ['confirm' => __('Are you sure you want to delete # {0}?', $profile->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination', []);?>
</div>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event[]|\Cake\Collection\CollectionInterface $events
 */
?>


<div class="events index large-9 medium-8 columns content">
    <h2><?= __('Events Overview') ?></h2>
    <section class="header__createbtn" id="actions-sidebar">
        <?=
            $this->Html->link('<i class="fa fa-plus"></i> New event', ['action' => 'add'],['escape'=>false,'class'=>'btn small-btn']);
        ?>
    </section>
    <table class="overview-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('image_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('startdate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('enddate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?= $this->Number->format($event->id) ?></td>
                <td><?= (isset($event['image_id'])) ? $this->Html->link($event['image_id'], ['controller' => 'Attachments', 'action' => 'view', $event['image_id']]) : '' ?></td>
                <!-- <td><?php /*$event->has('organisation') ? $this->Html->link($event->organisation->id, ['controller' => 'Organisations', 'action' => 'view', $event->organisation->id]) : '' */ ?></td>-->

                <td><?= h($event->name) ?></td>

                <td><?= h($event->startdate) ?></td>
                <td><?= h($event->enddate) ?></td>
                <td><?= h($event->created) ?></td>
                <td><?= h($event->modified) ?></td>
                <td><?= h($event->deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $event->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $event->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $event->id], ['confirm' => __('Are you sure you want to delete # {0}?', $event->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination', []);?>
</div>

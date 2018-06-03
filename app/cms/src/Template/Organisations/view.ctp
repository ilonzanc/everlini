<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Organisation $organisation
 */
?>



<?= $this->Html->link(__('Edit Organisation'), ['action' => 'edit', $organisation->id]) ?>
<?= $this->Form->postLink(__('<i class="fa fa-trash"></i> Delete'), ['action' => 'delete', $organisation->id],['escape'=>false,'class'=>'btn small-btn', 'confirm' => __('Are you sure you want to delete # {0}?', $organisation->id)]) ?>

<div class="organisations view large-9 medium-8 columns content">
    <h3><?= h($organisation->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($organisation->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($organisation->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($organisation->description)); ?>
    </div>
</div>

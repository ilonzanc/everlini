<h2>Roles Overview</h2>
<section class="header__createbtn" id="actions-sidebar">
    <?=
        $this->Html->link('<i class="fa fa-plus"></i> New role', ['action' => 'add'],['escape'=>false,'class'=>'btn small-btn']);
    ?>
</section>
<table class="overview-table" cellpadding="0" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Created</th>
        <th>Action</th>
    </tr>

    <?php foreach ($roles as $role): ?>
    <tr>
        <td>
            <?= $this->Html->link($role->name, ['action' => 'view', $role->id]) ?>
        </td>
        <td>
            <?= $role->created->format('d/m/Y H:i:s') ?>
        </td>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $role->id]) ?>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $role->id],
                ['confirm' => 'Are you sure?'])
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
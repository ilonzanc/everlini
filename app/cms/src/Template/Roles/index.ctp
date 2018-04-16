<h1>Roles</h1>
<?= $this->Html->link('Add Role', ['action' => 'add']) ?>
<table>
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
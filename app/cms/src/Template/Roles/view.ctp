<h1><?= h($role->name) ?></h1>
<p><?= h($role->body) ?></p>
<p><small>Created: <?= $role->created->format('d/m/Y H:i:s') ?></small></p>
<p><?= $this->Html->link('Edit', ['action' => 'edit', $role->id]) ?></p>
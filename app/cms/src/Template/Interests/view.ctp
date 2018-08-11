<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Interest $interest
 */
?>
<div class="interests view large-9 medium-8 columns content">
    <h2><?= h($interest->name) ?></h2>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($interest->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($interest->name) ?></td>
        </tr>
    </table>
    <h3>Parent</h3>
    <?= $interest->has('parent_interest') ? $this->Html->link($interest->parent_interest->name, ['controller' => 'Interests', 'action' => 'view', $interest->parent_interest->id]) : '' ?>
    <h3>Children</h3>
    <ul>
        <?php foreach ($descendants as $descendant) :?>
        <li><?php echo $descendant->name ?></li>
        <?php endforeach;?>
    </ul>
    <h3>Relatives</h3>
    <ul>
        <?php
            foreach ($relatives as $relative) :
            if ($relative->id != $interest->id) :
        ?>
            <li><?php echo $relative->name ?></li>
        <?php
            endif;
            endforeach;
        ?>
    </ul>
</div>

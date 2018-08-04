<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin $admin
 */
?>
<div class="admins view large-9 medium-8 columns content">
    <h2><?= h($admin->username) ?></h2>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Profile') ?></th>
            <td><?= $admin->has('profile') ? $this->Html->link($admin->profile->id, ['controller' => 'Profiles', 'action' => 'view', $admin->profile->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($admin->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($admin->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Organisations') ?></h4>
        <?php if (!empty($admin->organisations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
            </tr>
            <?php foreach ($admin->organisations as $organisations): ?>
            <tr>
                <td><?= h($organisations->name) ?></td>
                <td><?= h($organisations->description) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

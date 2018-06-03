<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
?>
<div class="posts form large-9 medium-8 columns content">
    <?= $this->Form->create($post, ['enctype' => 'multipart/form-data']) ?>
        <h2><?= __('Add Post') ?></h2>
        <?php
            echo $this->Form->control('event_id', ['options' => $events]);
            echo $this->Form->control('title');
            echo $this->Form->control('body');
            echo $this->Form->control('published');
        ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

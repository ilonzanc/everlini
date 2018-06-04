<!-- Users Login Template -->
<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('email', [
    'placeholder' => 'Your emailaddress...'
]) ?>
<?= $this->Form->control('password', [
    'placeholder' => '********'
]) ?>
<?= $this->Form->button('Login') ?>
<?= $this->Form->end() ?>
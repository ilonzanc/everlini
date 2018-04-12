<h1>Add Role</h1>
<?php
    echo $this->Form->create($role);
    // Hard code the user for now.
    echo $this->Form->control('name');
    echo $this->Form->button(__('Save Role'));
    echo $this->Form->end();
?>
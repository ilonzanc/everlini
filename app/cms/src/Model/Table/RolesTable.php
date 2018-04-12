<?php
// src/Model/Table/RolesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RolesTable extends Table
{
    // populate created and update with timestamps
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    // formvalidation
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name')
            ->minLength('name', 4)
            ->maxLength('name', 20);

        return $validator;
    }
}
<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Organisation Entity
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string $description
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Admin[] $admins
 */
class Organisation extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'creator_id' => true,
        'name' => true,
        'description' => true,
        'slug' => true,
        'user' => true,
        'admins' => true
    ];
}

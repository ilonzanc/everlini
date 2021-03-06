<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Event Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\FrozenTime $startdate
 * @property \Cake\I18n\FrozenTime $enddate
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Favorite[] $favorites
 * @property \App\Model\Entity\Post[] $posts
 */
class Event extends Entity
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
        'organisation_id' => true,
        'image_id' => true,
        'name' => true,
        'description' => true,
        'address' => true,
        'lng' => true,
        'lat' => true,
        'startdate' => true,
        'enddate' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'organisation' => true,
        'image' => true,
        'favorites' => true,
        'posts' => true,
        'interests' => true
    ];
}

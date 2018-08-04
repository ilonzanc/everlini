<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Profile Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $firstname
 * @property string $lastname
 * @property \Cake\I18n\FrozenDate $dateofbirth
 * @property string $streetname
 * @property string $housenr
 * @property string $city
 * @property string $country
 * @property bool $organisation
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Event[] $events
 * @property \App\Model\Entity\Favorite[] $favorites
 */
class Profile extends Entity
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
        'user_id' => true,
        'firstname' => true,
        'lastname' => true,
        'dateofbirth' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'events' => true,
        'favorites' => true
    ];
}

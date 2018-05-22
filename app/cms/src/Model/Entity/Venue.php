<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


/**
 * Venue Entity
 *
 * @property int $id
 * @property string $name
 * @property string $streetname
 * @property string $housenr
 * @property string $city
 * @property string $country
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Event[] $events
 */
class Venue extends Entity
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
        'name' => true,
        'streetname' => true,
        'housenr' => true,
        'city' => true,
        'country' => true,
        'created' => true,
        'modified' => true,
        'events' => true
    ];
}

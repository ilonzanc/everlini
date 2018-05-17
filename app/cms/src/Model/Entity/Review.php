<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $rating
 * @property int $profile_id
 * @property int $event_id
 *
 * @property \App\Model\Entity\Profile $profile
 * @property \App\Model\Entity\Event $event
 */
class Review extends Entity
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
        'title' => true,
        'body' => true,
        'rating' => true,
        'profile_id' => true,
        'event_id' => true,
        'profile' => true,
        'event' => true
    ];
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @property \App\Model\Table\FavoritesTable|\Cake\ORM\Association\HasMany $Favorites
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\HasMany $Posts
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EventsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('events');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Chris48s/GeoDistance.GeoDistance', [
            'latitudeColumn' => 'lat',
            'longitudeColumn' => 'lng',
            'units' => 'km'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always',
                ],
                'Events.softDelete' => [
                    'deleted' => 'always'
                ]
            ]
        ]);

        $this->belongsTo('Organisations', [
            'foreignKey' => 'organisation_id'
        ]);

        $this->belongsTo('Attachments', [
            'foreignKey' => 'image_id',
        ]);

        $this->hasMany('Favorites', [
            'foreignKey' => 'event_id'
        ]);

        $this->hasMany('Posts', [
            'foreignKey' => 'event_id'
        ]);

        $this->belongsToMany('Interests', [
            'joinTable' => 'events_interests',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->dateTime('startdate')
            ->allowEmpty('startdate');

        $validator
            ->dateTime('enddate')
            ->allowEmpty('enddate');

        $validator
            ->scalar('meetup_id')
            ->allowEmpty('meetup_id');

        $validator
            ->scalar('meetup_groupname')
            ->allowEmpty('meetup_groupname');

        return $validator;
    }

    public function validationMeetup($validator)
    {

        $validator
            ->scalar('meetup_id')
            ->notEmpty('meetup_id');

        $validator
            ->scalar('meetup_groupname')
            ->notEmpty('meetup_groupname');

        $validator
            ->scalar('name')
            ->allowEmpty('name');


        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['organisation_id'], 'Organisations'));

        return $rules;
    }
}

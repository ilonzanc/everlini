<?php
// src/Api/Controller/EventsController.php
namespace App\Controller\Api;
use App\Controller\AppController;
use Cake\I18n\Time;

use Cake\Event\Event;

class EventsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'getEventsByOrganisationId', 'edit', 'view', 'delete']);

        //TODO: clean this up. Avoid repetitive code.

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin', '*')->
            withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')->
            withHeader('Access-Control-Allow-Headers',
                       'Accept, Authorization, Cache-Control, Content-Type, X-Requested-With, x-csrf-token')->
            withHeader('Access-Control-Allow-Credentials', 'true')->
            withHeader('Access-Control-Max-Age', '3600');

        $this->response->send();
    }

    public function index()
    {

        $message = "";

        $options = [
            'latitude' => $this->request->data['location']['lat'],
            'longitude' => $this->request->data['location']['lng'],
            'radius' => $this->request->data['radiusValue']
        ];


        $startdate = new Time($this->request->data['startdate']);
        $enddate = new Time($this->request->data['enddate']);
        $enddate->modify('+1 days');

        $interests = array();

        $tags = array();

        $events = array();

        $conditions = array();

        $conditions[] = array('Events.startdate', $startdate, $enddate);

        $search_terms = $this->request->data['interests'];

        foreach($search_terms as $search_term){
            $interests[] = array('Events.name Like' =>'%'.$search_term.'%');
            $interests[] = array('Events.description Like' =>'%'.$search_term.'%');
            $tags[] = array('Interests.name Like' =>'%'.$search_term.'%');
        }

        $events = $this->Events
            ->find('bydistance', $options)
            ->select($this->Events)
            ->where(function($exp) use ($startdate, $enddate) {
                return $exp->between('Events.startdate', $startdate, $enddate);
            })
            ->where(function($q) use ($interests) {
                return $q->or_($interests);
            })
            ->where('Events.deleted IS NULL')
            ->contain('Organisations');

        $additionalEvents = $this->Events->find('all')
            ->contain('Interests', function ($q) use ($tags) {
                return $q->where(function($t) use ($tags){
                    return $t->or_($tags);
                });
            });

        $data = $this->request->data;

        $this->set([
            'additionalEvents' => $additionalEvents,
            'events' => $events,
            'additionalEvents' => $additionalEvents,
            '_serialize' => ['events', 'additionalEvents', 'additionalEvents']
        ]);
    }

    public function view($id)
    {
        $event = $this->Events->find()
        ->contain(
            'Posts', function ($q) {
                return $q
                    ->where(['Posts.deleted IS NULL']);
        })
        ->contain('Interests')
        ->contain('Attachments')
        ->where(['Events.id' => $id]);

        $this->set([
            'event' => $event,
            '_serialize' => ['event']
        ]);
    }

    public function add()
    {
        $message = [];

        if ($this->request->is('post')) {
            if (!isset($this->request->data['meetup_id'])){
                $event = $this->Events->newEntity();

                $event->organisation_id = $this->request->data['organisation_id'];
                $event->name = $this->request->data['name'];
                $event->description = $this->request->data['description'];

                //saving the startdate
                $startdate = $this->request->data['startdate']['date'];
                $starttime = $this->request->data['startdate']['time'];
                $fullstartdate = $startdate . ' ' . $starttime;
                $event->startdate = strtotime($fullstartdate);

                //saving the enddate
                $enddate = $this->request->data['enddate']['date'];
                $endtime = $this->request->data['enddate']['time'];
                $fullenddate = $enddate . ' ' . $endtime;
                $event->enddate = strtotime($fullenddate);

                $event->address = $this->request->data['location']['name'];
                $event->lat = $this->request->data['location']['lat'];
                $event->lng = $this->request->data['location']['lng'];

                $event->image_id =  $this->request->data['image_id'];

                if ($this->Events->save($event)) {
                    $message = 'The event has been saved.';
                    $errors = $event->errors();

                    if(isset($this->request->data['meetup_id'])) {
                        $event = json_encode($event);
                        $this->response->type('json');
                        $this->response->body($event);
                        return $this->response;
                    }

                    $this->loadModel('Interests');

                    foreach($this->request->data['tags'] as $tag) {
                        $existInterest = $this->Interests->find('all')
                        ->where(['Interests.name' => $tag]);
                        $existInterest = $existInterest->first();
                        if ($existInterest == null) {
                            $data = [
                                'name' => $tag,
                                'parent_id' => null,
                                'events' => [
                                    [
                                        'id' => $event->id,
                                    ],
                                ]
                            ];
                            $interest = $this->Interests->newEntity($data, [
                                'associated' => ['Events']
                            ]);
                            if ($this->Interests->save($interest)) {
                                $message = 'Saved interests with event';
                            } else {
                                $message = 'Error: somethign went wroing with the interests';
                            }
                        }
                    }
                }
                else {
                    $message = 'The event could not be saved. Please, try again.';
                    $errors = $event->errors();
                }

                $this->set([
                    'existInterest' => $existInterest,
                    'message' => $message,
                    'event' => $event,
                    'errors' => $errors,
                    '_serialize' => ['query', 'message', 'event', 'errors', 'existInterest']
                ]);

            } else {
                $query = $this->Events->find()
                    ->where(['meetup_id' => $this->request->data['meetup_id']]);

                $query = $query->first();

                if(empty($query)) {
                    $event = $this->Events->newEntity(
                        $this->request->getData(),
                        ['validate' => 'meetup']
                    );

                    $event->meetup_id = $this->request->data['meetup_id'];
                    $event->meetup_groupname = $this->request->data['group']['urlname'];

                    $event->name = null;
                    $event->description = null;
                    $event->startdate = null;

                    if ($this->Events->save($event)) {
                        $message = 'The event has been saved.';
                        $errors = $event->errors();

                        $event = json_encode($event);
                        $this->response->type('json');
                        $this->response->body($event);
                        return $this->response;

                    }
                    else {
                        $message = 'The event could not be saved. Please, try again.';
                    }

                } else {
                    $query = json_encode($query);
                    $this->response->type('json');
                    $this->response->body($query);
                    return $this->response;
                }
            }

        }


    }

    public function edit($id)
    {
        $event = $this->Events->get($id);
        $message = "Couldn't update event";
        if ($this->request->is(['post', 'put'])) {
            $event->organisation_id = $this->request->data['organisation_id'];
            $event->name = $this->request->data['name'];
            $event->description = $this->request->data['description'];

            //saving the startdate
            $startdate = $this->request->data['startdate']['date'];
            $starttime = $this->request->data['startdate']['time'];
            $fullstartdate = $startdate . ' ' . $starttime;
            $event->startdate = strtotime($fullstartdate);

            //saving the enddate
            $enddate = $this->request->data['enddate']['date'];
            $endtime = $this->request->data['enddate']['time'];
            $fullenddate = $enddate . ' ' . $endtime;
            $event->enddate = strtotime($fullenddate);

            $event->address = $this->request->data['location']['name'];
            $event->lat = $this->request->data['location']['lat'];
            $event->lng = $this->request->data['location']['lng'];

            if ($this->Events->save($event)) {
                $message = 'The event was updated.';
            }
            else {
                $message = 'The event could not be updated. Please, try again.';
            }


        } else {
            $message = "Request was not a post or put request";
        }

        $this->set([
            'event' => $event,
            'message' => $message,
            '_serialize' => ['message', 'event']
        ]);
    }

    public function getEventsByOrganisationId($organisationid)
    {
        $this->autoRender = false;
        $this->viewBuilder()->setLayout(null);

        $message = "";

        $events = $this->Events->find('all', [
            'conditions' => ['Events.organisation_id' => $organisationid, 'Events.deleted IS NULL' ]
        ]);

        $events = json_encode($events);
        $this->response->type('json');
        $this->response->body($events);
        return $this->response;
    }

    public function delete($id)
    {
        $message = "Can't delete event";
        $event = $this->Events->get($id);
        $this->Events->touch($event, 'Events.softDelete');

        if ($this->Events->save($event)) {
            $message = 'Deleted';
        }

        $this->set([
            'message' => $message,
            'event' => $event,
            '_serialize' => ['message', 'event']
        ]);
    }

    public function getEventsWithinRadius()
    {
        $this->autoRender = false;

        $data = $this->request->data;

        $conditions = array(
            'NOT' => array(
                'Events.lat' => null,
                'Events.lng' => null
            )
        );

        $radius = $this->request->data['radiusValue'];

        $fields = array('*');
        $group = false;
        if (!empty($data['location']['name']) && !empty($data['location']['lat']) && !empty($data['location']['lng'])) {
            $fields[] = '(6371 * acos(
                cos(radians(' . $data['location']['lat'] . ')) * cos(radians(Events.lat )) *
                cos(radians(Events.lng) - radians(' . $data['location']['lng'] . ')) +
                sin(radians(' . $data['location']['lat'] . ')) *
                sin(radians(Events.lat))
                )) AS `distance`';
            $group = 'Events.id HAVING distance <= ' . $radius;
        } elseif (!empty($data['location']['name'])) {
            $conditions['Events.address LIKE'] = '%' . $data['location']['name'] . '%';
        } else {
            $message = 'tis kapot';
        }

        $data = $this->Events->find('all', array(
            'fields' => $fields,
            'group' => $group,
            'conditions' => $conditions,
        ));

        return $data;

        $this->set([
            'message' => $message,
            'data' => $data,
            '_serialize' => ['message', 'data']
        ]);

    }
}
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
        $this->Auth->allow(['add', 'getEventsByUser', 'edit', 'view', 'delete']);

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

        $data = $this->request->data;

        $conditions = array();

        $radius = $data['radiusValue'];

        $fields = array('*');
        $group = false;
        if (!empty($data['location']['name']) && !empty($data['location']['lat']) && !empty($data['location']['lng'])) {
            $fields = '((ACOS(SIN(' . $data['location']['lat'] .
            ' * PI() / 180) * SIN(Events.lat * PI() / 180) + COS(' .
            $data['location']['lat'] .
            ' * PI() / 180) * COS(Events.lat * PI() / 180) * COS((' .
            $data['location']['lng'] .
            ' - Events.lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance ';
            $group = 'Events HAVING distance <= ' . $radius;
            $message = "All fields were filled in.";
        } elseif (!empty($data['location']['name'])) {
            $conditions['Events.address LIKE'] = '%' . $data['location']['name'] . '%';
            $message = "Only address was filled in";
        } else {
            $message = 'Nothings was filled in....';
        }

        $events = $this->Events->find('all', array(
            'fields' => $fields,
            'group' => $group,
            'conditions' => $conditions,
        ));



        /* $events = json_encode($events->sql());
                        $this->response->type('json');
                        $this->response->body($events);
                        return $this->response; */

        $this->set([
            'message' => $message,
            'events' => $events,
            '_serialize' => ['message', 'events']
        ]);

        /* die;
        $startdate = new Time($startdate);
        $enddate = new Time($enddate);
        $enddate->modify('+1 days');

        $interests = array();

        $events = array();

        $conditions = array();

        $conditions[] = array('Events.startdate', $startdate, $enddate);
        $conditions[] = array('Events.city' => $location);

        $search_terms = $this->request->data['interests'];

        foreach($search_terms as $search_term){
            $interests[] = array('Events.name Like' =>'%'.$search_term.'%');
            $interests[] = array('Events.description Like' =>'%'.$search_term.'%');
        }

        $events = $this->Events->find('all', [
            'contain' => ['Users'],
            'conditions' => array('OR' => $interests)
        ]);

        $events->where(['Events.city' => $location])
        ->where(function($exp) use ($startdate, $enddate) {
            return $exp->between('Events.startdate', $startdate, $enddate);
        });

        $this->set([
            'conditions' => $conditions,
            'events' => $events,
            'search_terms' => $search_terms,
            '_serialize' => ['events', 'search_terms', 'conditions']
        ]); */
    }

    public function view($id)
    {
        $event = $this->Events->find()
        ->contain(
            'Posts', function ($q) {
                return $q
                    ->where(['Posts.deleted IS NULL']);
        })
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
            if ($this->request->data['meetup_id'] == null) {
                $event = $this->Events->newEntity();

                $event->user_id = $this->request->data['user_id'];
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

                if ($this->Events->save($event)) {
                    $message = 'The event has been saved.';
                    $errors = $event->errors();

                    if($this->request->data['meetup_id']) {
                        $event = json_encode($event);
                        $this->response->type('json');
                        $this->response->body($event);
                        return $this->response;
                    }
                }
                else {
                    $message = 'The event could not be saved. Please, try again.';
                    $errors = $event->errors();
                }

                $this->set([
                    'data' => $event,
                    'message' => $message,
                    'event' => $event,
                    'errors' => $errors,
                    '_serialize' => ['query', 'data', 'message', 'event', 'errors']
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
            $event->user_id = $this->request->data['user_id'];
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

            $event->street = $this->request->data['street'];
            $event->housenr = $this->request->data['housenr'];
            $event->postal_code = $this->request->data['postal_code'];
            $event->city = $this->request->data['city'];
            $event->country = $this->request->data['country'];

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
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    public function getEventsByUser()
    {
        $this->autoRender = false;
        $this->viewBuilder()->setLayout(null);

        $message = "";

        $user_id = $this->request->query('user');

        $events = $this->Events->find('all', [
            'conditions' => ['Events.user_id' => $user_id, 'Events.deleted IS NULL' ]
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
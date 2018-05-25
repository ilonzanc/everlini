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
        $this->Auth->allow(['add', 'getEventsByUser', 'edit', 'view']);

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin', '*')->
            withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')->
            withHeader('Access-Control-Allow-Headers',
                       'Accept, Authorization, Cache-Control, Content-Type, X-Requested-With, x-csrf-token')->
            withHeader('Access-Control-Allow-Credentials', 'true')->
            withHeader('Access-Control-Max-Age', '3600');

        $this->response->send();
    }

    public function index($location, $startdate, $enddate)
    {
        $startdate = new Time($startdate);
        $enddate = new Time($enddate);
        $enddate->modify('+1 days');

        $events = $this->Events->find('all')->contain([
            'Users' => []
        ])
        ->where(function($exp) use ($startdate, $enddate) {
            return $exp->between('Events.startdate', $startdate, $enddate);
        })
        ->where(['Events.city' => $location]);

        $this->set([
            'events' => $events,
            '_serialize' => ['events']
        ]);
    }

    public function view($id)
    {
        $event = $this->Events->get($id, array(
        ));
        $this->set([
            'event' => $event,
            '_serialize' => ['event']
        ]);
    }

    public function add()
    {


        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
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

            $event->street = $this->request->data['location']['street'];
            $event->housenr = $this->request->data['location']['housenr'];
            $event->postal_code = $this->request->data['location']['postal_code'];
            $event->city = $this->request->data['location']['city'];
            $event->country = $this->request->data['location']['country'];

            if ($this->Events->save($event)) {
                $message= 'The event has been saved.';
            }
            else {
                $message= 'The event could not be saved. Please, try again.';
            }

            $this->set([
                'message' => $message,
                'event' => $event,
                'userid' => $userid,
                '_serialize' => ['message', 'event', 'userid']
            ]);

        }


    }

    public function edit($id)
    {
        $event = $this->Events->get($id);
        $message = "bitch";
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
                $message= 'The event was updated.';
            }
            else {
                $message= 'The event could not be updated. Please, try again.';
            }


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

        $message = "feck";

        $user_id = $this->request->query('user');

        $events = $this->Events->find('all', [
            'conditions' => ['Events.user_id' => $user_id]
        ]);

        $events = json_encode($events);
        $this->response->type('json');
        $this->response->body($events);
        return $this->response;
    }

    public function delete($id)
    {
        $event = $this->Events->get($id);
        $message = 'Deleted';
        if (!$this->Events->delete($event)) {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
}
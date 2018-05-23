<?php
// src/Api/Controller/EventsController.php
namespace App\Controller\Api;
use App\Controller\AppController;
use Cake\I18n\Time;

class EventsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index($location, $startdate, $enddate)
    {
        $startdate = new Time($startdate);
        $enddate = new Time($enddate);
        $enddate->modify('+1 days');

        $events = $this->Events->find('all')->contain([
            'Venues' => [
                'queryBuilder' => function ($q) use ($location) {
                    return $q
                        ->where(['Venues.city' => $location]);
                }
            ],
            'Profiles' => []
        ])
        ->where(function($exp) use ($startdate, $enddate) {
            return $exp->between('Events.startdate', $startdate, $enddate);
       });

        $this->set([
            'events' => $events,
            '_serialize' => ['events']
        ]);
    }

    public function view($id)
    {
        $event = $this->Events->get($id, array(
            'contain' => array("Venues")
        ));
        $this->set([
            'event' => $event,
            '_serialize' => ['event']
        ]);
    }

    public function add()
    {
        $event = $this->Events->newEntity($this->request->getData());
        if ($this->Events->save($event)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'event' => $event,
            '_serialize' => ['message', 'event']
        ]);
    }

    public function edit($id)
    {
        $event = $this->Events->get($id);
        if ($this->request->is(['post', 'put'])) {
            $event = $this->Events->patchEntity($event, $this->request->getData());
            if ($this->Events->save($event)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
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
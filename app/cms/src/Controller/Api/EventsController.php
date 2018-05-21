<?php
// src/Api/Controller/EventsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

class EventsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $events = $this->Events->find('all', array(
            'contain' => array("Venues", "Profiles"),
            //'conditions' => array("Events.venue_id" => $location)
        ));
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
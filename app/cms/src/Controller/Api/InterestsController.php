<?php
// src/Api/Controller/InterestsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

use Cake\Event\Event;

class InterestsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'add', 'edit', 'view', 'delete', 'getParentInterests']);

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
        $interests = $this->Interests->find('threaded', array(
            'order' => 'rand()'
        ));

        $this->set([
            'interests' => $interests,
            '_serialize' => ['interests']
        ]);
    }

    public function view($id)
    {
        $interest = $this->Interests->get($id);
        $this->set([
            'interest' => $interest,
            '_serialize' => ['interest']
        ]);
    }

    public function add()
    {
        $interest = $this->Interests->newEntity($this->request->getData());
        if ($this->Interests->save($interest)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'interest' => $interest,
            '_serialize' => ['message', 'interest']
        ]);
    }

    public function getParentInterests()
    {
        $interests = $this->Interests->find('all')
        ->contain(['ChildInterests'])
        ->where(['Interests.parent_id IS NULL']);

        $interests = $this->Interests->find('threaded');

        $this->set([
            'interests' => $interests,
            '_serialize' => ['interests']
        ]);
    }
}
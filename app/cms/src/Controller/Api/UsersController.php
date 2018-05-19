<?php
// src/Api/Controller/UsersController.php
namespace App\Controller\Api;
use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\Event\Event;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Csrf');
    }

    public function index()
    {
        $users = $this->Users->find('all');
        $this->set([
            'users' => $users,
            '_serialize' => ['users']
        ]);
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set([
            'user' => $user,
            '_serialize' => ['user']
        ]);
    }

    public function login()
    {
        $message = '';
        //$token = $this->request->getParam('_csrfToken');
        //if ($this->request->is('post')) {
            $token = $this->request->getParam('_csrfToken');
            $user = $this->Auth->identify();
            if ($user) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        //}

        $this->set([
            'token' => $token,
            'message' => $message,
            '_serialize' => ['message', 'token']
        ]);;
    }

    public function token() {
        $token = $this->request->getParam('_csrfToken');

        $this->set([
            'token' => $token,
            '_serialize' => ['token']
        ]);
    }

    public function beforeFilter(Event $event)
    {
        $this->getEventManager()->off($this->Csrf);
    }
}
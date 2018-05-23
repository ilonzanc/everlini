<?php
// src/Api/Controller/UsersController.php
namespace App\Controller\Api;
use App\Controller\AppController;

use Cake\Event\Event;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->type('application/json');
        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            'storage' => 'Session'
        ]);
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow('login');
    }

    public function login()
    {
        $this->autoRender = false;
        $this->viewBuilder()->setLayout(null);



        //check if reauest data is post, request is currently get???????

        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
            $this->loadModel('Users');
            $id = $this->Auth->user('id');
            $loggedInUser = $this->Users->get($id, [
                'fields' => array('id', 'email', 'username'),
                'contain' => array(
                    'Profiles' => array(
                        'fields' => array(
                            'Profiles.user_id',
                            'Profiles.id',
                            'Profiles.firstname',
                            'Profiles.lastname'
                        ),
                    )
                )
            ]);
            $message = $loggedInUser;
        } else {
            $message = 'Error. Log in failed. Please, try again.';
        }

        $message = json_encode($message);
        $this->response->type('json');
        $this->response->body($message);
        return $this->response;
    }


}

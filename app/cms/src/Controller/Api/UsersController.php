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
        $this->Auth->allow(['login', 'register', 'logout']);

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin', '*')->
            withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')->
            withHeader('Access-Control-Allow-Headers',
                       'Accept, Authorization, Cache-Control, Content-Type, X-Requested-With, x-csrf-token')->
            withHeader('Access-Control-Allow-Credentials', 'true')->
            withHeader('Access-Control-Max-Age', '3600');

        $this->response->send();
    }

    public function register()
    {
        $this->loadModel('Users');
        $this->loadModel('Profiles');
        $user = $this->Users->newEntity();

        //check if reauest is post
        $shortFirstName = substr($this->request->data['firstname'], 0, 4);
        $shortLastName = substr($this->request->data['lastname'], 0, 4);
        $dobShort = substr(
            $this->request->data['dateofbirth'],
            strlen($this->request->data['dateofbirth']) - 4,
            strlen($this->request->data['dateofbirth'])
        );

        $newusername = $shortFirstName . $shortLastName .$dobShort;

        $user->role_id = (int)$this->request->data['role_id'];
        $user->username = $newusername;
        $user->email = $this->request->data['email'];
        $user->password = $this->request->data['password'];

        $username = $user->username;

        if ($this->Users->save($user)) {
            $message = 'The user has been saved.';
            $username = $user->username;
        } else {
            $message = 'The user could not be saved. Please, try again.';
        }

        $profile = $this->Profiles->newEntity();
        $newuser = $this->Users->find('all')
            ->where(['Users.username =' => $username]);

        //find new created user
        $newuser = $newuser->first();

        //create new profile with new user id as user_id

        $data = $this->request->data;
        $profile->user_id = $newuser->id;
        $profile->firstname = $data['firstname'];
        $profile->lastname = $data['lastname'];

        $profile->dateofbirth = strtotime($data['dateofbirth']);

        $profile->organisation = $data['organisation'];

        if ($this->Profiles->save($profile)) {
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
            $message = "The profile could not be saved. Please, try again.";
        }

        $message = json_encode($message);
        $this->response->type('json');
        $this->response->body($message);
        return $this->response;

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

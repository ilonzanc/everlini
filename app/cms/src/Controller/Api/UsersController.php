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

        //TODO: clean this up. Avoid repetitive code.

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
        $this->loadModel('Roles');
        $user = $this->Users->newEntity();
        //check if reauest is post

        $user->email = $this->request->data['email'];
        $user->password = $this->request->data['password'];

        $roles = $this->Roles->find('all')
        ->where(["Roles.name" => $this->request->data['role_name']]);

        $role = $roles->first();

        $role_id = $role->id;

        $user->role_id = $role_id;
        $user->username = $this->request->data['username'];
        $newuserid = "";

        if ($this->Users->save($user)) {
            $message = 'The user has been saved.';
            $newuserid = $user->id;

        } else {
            $error['errors'][] = 'We kunnen dit profiel niet opslaan. Probeer het opnieuw';
           /*  $error = json_encode($error);
            $this->response->type('json');
            $this->response->body($error);
            return $this->response; */
            $this->set([
                'user' => $user,
                'errors' => $error,
                '_serialize' => ['errors', 'user']
            ]);
        }
        $this->loadModel('Interests');

        foreach($this->request->data['interests'] as $interest) {
            $existInterest = $this->Interests->find('all')
            ->where(['Interests.name' => $interest]);
            $existInterest = $existInterest->first();
            if ($existInterest == null) {
                $data = [
                    'name' => $interest,
                    'parent_id' => null,
                    'users' => [
                        [
                            'id' => $user->id,
                        ],
                    ]
                ];
                $interest = $this->Interests->newEntity($data, [
                    'associated' => ['Users']
                ]);
                if ($this->Interests->save($interest)) {
                    $message = 'Saved interests with user';
                } else {
                    $message = 'Error: somethign went wrong with the interests';
                }
            }
        }
        $this->loadModel('Profiles');
        $profile = $this->Profiles->newEntity();

        //create new profile with new user id as user_id
        $data = $this->request->data;
        $profile->user_id = $newuserid;
        $profile->firstname = $data['firstname'];
        $profile->lastname = $data['lastname'];
        $profile->dateofbirth = strtotime($data['dateofbirth']);
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
            $error['errors'][] = 'We kunnen dit profiel niet opslaan. Probeer het opnieuw';
            $error = json_encode($error);
            $this->response->type('json');
            $this->response->body($error);
            return $this->response;
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
            $loggedInUser = json_encode($loggedInUser);
            $this->response->type('json');
            $this->response->body($loggedInUser);
            return $this->response;

        } else {
            $error['errors'][] = 'Email of wachtwoord komen niet overeen';
            $error = json_encode($error);
            $this->response->type('json');
            $this->response->body($error);
            return $this->response;
        }


    }


}

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
        $user = $this->Users->newEntity();
        //check if reauest is post
        $shortFirstName = substr($this->request->data['firstname'], 0, 4);
        $shortLastName = substr($this->request->data['lastname'], 0, 4);
        $dobShort = substr(
            $this->request->data['dateofbirth'],
            strlen($this->request->data['dateofbirth']) - 4,
            strlen($this->request->data['dateofbirth'])
        );
        $user->email = $this->request->data['email'];
        $user->password = $this->request->data['password'];
        $user->role_id = $this->request->data['role_id'];
        $newuserid = "";

        if ($this->Users->save($user)) {
            $message = 'The user has been saved.';
            $newuserid = $user->id;

        } else {
            $message = 'The user could not be saved. Please, try again.';
        }

        if ($this->request->data['role_id'] == 2) {
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
                    'fields' => array('id', 'email'),
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

        } elseif ($this->request->data['role_id'] == 3) {
            $this->loadModel('Organisations');
            $organisation = $this->Organisations->newEntity();

            //create new profile with new user id as user_id
            $data = $this->request->data;
            $organisation->user_id = $newuserid;
            $organisation->name = $data['name'];
            $organisation->description = $data['description'];

            if ($this->Organisations->save($organisation)) {
                $this->Auth->setUser($user);
                $this->loadModel('Users');
                $id = $this->Auth->user('id');
                $loggedInUser = $this->Users->get($id, [
                    'fields' => array('id', 'email'),
                    'contain' => array(
                        'Organisations' => array(
                            'fields' => array(
                                'Organisations.user_id',
                                'Organisations.id',
                                'Organisations.name',
                            ),
                        )
                    )
                ]);
                $message = $loggedInUser;
            } else {
                $message = "The organisation could not be saved. Please, try again.";
            }
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
                'fields' => array('id', 'email'),
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
            $error['errors'][] = 'Email of wachtwoord komen niet overeen';
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


}

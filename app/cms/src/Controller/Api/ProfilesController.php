<?php
// src/Api/Controller/ProfilesController.php
namespace App\Controller\Api;
use App\Controller\AppController;

use Cake\Event\Event;

class ProfilesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'getProfileByUsername', 'edit', 'view', 'delete']);

        //TODO: clean this up. Avoid repetitive code.s

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
        $profiles = $this->Profiles->find('all');
        $this->set([
            'profiles' => $profiles,
            '_serialize' => ['profiles']
        ]);
    }

    public function view($id)
    {

        $profile = $this->Profiles->get($id);

        $profile = $this->Profiles->find('all')
        ->where(['Profiles.id' => $id])
        ->contain([
            'Users' => ['Interests']
        ]);

        $profile = $profile->first();

        $this->set([
            'profile' => $profile,
            '_serialize' => ['profile']
        ]);
    }

    public function register()
    {
        $this->loadModel('Users');
        $username = null;
        $user = $this->Users->newEntity();

        //TODO: check if reauest is post
        $dobShort = substr(
            $this->request->data['dateofbirth'],
            strlen($this->request->data['dateofbirth']) - 2,
            strlen($this->request->data['dateofbirth'])
        );

        $user->role_id = (int)$this->request->data['role_id'];
        $user->username = $this->request->data['username'];
        $user->email = $this->request->data['email'];
        $user->password = $this->request->data['password'];

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

        $profile->streetname = $data['streetname'];
        $profile->housenr = $data['housenr'];
        $profile->city = $data['city'];
        $profile->country = $data['country'];
        $profile->organisation = $data['organisation'];

        if ($this->Profiles->save($profile)) {
            $message = 'The profile has been saved.';
        } else {
            $message = "The profile could not be saved. Please, try again.";
        }


        $this->set([
            'message' => $message,
            'profile' => $profile,
            '_serialize' => ['message', 'profile']
        ]);
    }

    public function edit($id)
    {
        $profile = $this->Profiles->get($id);
        if ($this->request->is(['post', 'put'])) {
            $profile->firstname = $this->request->data['firstname'];
            $profile->lastname = $this->request->data['lastname'];
            $profile->dateofbirth = strtotime($this->request->data['dateofbirth']);
            if ($this->Profiles->save($profile)) {
                $message = 'Saved the profile';
                $this->loadModel('Users');
                $users = $this->Users->find('all')
                    ->where(['Users.id' => $this->request->data['user_id']]);
                $user = $users->first();
                $user->username = $this->request->data['user']['username'];
                $user->email = $this->request->data['user']['email'];

                if ($this->Users->save($user)) {
                    $message = 'The user has been saved.';

                    $this->loadModel('Interests');

                    foreach($this->request->data['user']['interests'] as $interest) {
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
                                $message = 'Saved interests with event';
                            } else {
                                $message = 'Error: somethign went wroing with the interests';
                            }
                        }
                    }
                } else {
                    $message = "The user could not be saved. Please, try again.";
                }
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'user' => $user,
            'message' => $message,
            '_serialize' => ['message', 'user']
        ]);
    }

    public function delete($id)
    {
        $profile = $this->Profiles->get($id);
        $message = 'Deleted';
        if (!$this->Profiles->delete($profile)) {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    public function getProfileByUsername($username)
    {

        $this->loadModel('Users');

        $users = $this->Users->find('all')
        ->where(['Users.username' => $username]);

        $user = $users->first();

        $profile = $this->Profiles->find('all')
        ->where(['Profiles.user_id' => $user->id])
        ->contain('Users')
        ->contain('Users.Interests', function ($q) {
            return $q
                ->select(['name']);
        });

        $profile = $profile->first();

        $this->set([
            'profile' => $profile,
            '_serialize' => ['profile']
        ]);
    }
}
<?php
// src/Api/Controller/ProfilesController.php
namespace App\Controller\Api;
use App\Controller\AppController;

class ProfilesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
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
        ->contain('Users');

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
        $shortFirstName = substr($this->request->data['firstname'], 0, 3);
        $shortLastName = substr($this->request->data['lastname'], 0, 3);
        $dobShort = substr(
            $this->request->data['dateofbirth'],
            strlen($this->request->data['dateofbirth']) - 2,
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
            $profile = $this->Profiles->patchEntity($profile, $this->request->getData());
            if ($this->Profiles->save($profile)) {
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
}
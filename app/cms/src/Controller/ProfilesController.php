<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\User;

/**
 * Profiles Controller
 *
 * @property \App\Model\Table\ProfilesTable $Profiles
 *
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfilesController extends AppController
{

    public $uses = ['User'];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $profiles = $this->paginate($this->Profiles);

        $this->set(compact('profiles'));
    }

    /**
     * View method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $profile = $this->Profiles->get($id, [
            'contain' => ['Users', 'Events', 'Favorites']
        ]);

        $this->set('profile', $profile);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */

    public function add()
    {
        // First create a user
        $this->loadModel('Users');
        $username = null;
        $user = $this->Users->newEntity();
        $newuserid = "";
        if ($this->request->is('post')) {
            $user->role_id = (int)$this->request->data['role_id'];
            $user->email = $this->request->data['email'];
            $user->password = $this->request->data['password'];

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                $newuserid = $user['id'];
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }

        }

        $profile = $this->Profiles->newEntity();

        //create new profile with new user id as user_id
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $profile->user_id = $newuserid;
            $profile->firstname = $data['firstname'];
            $profile->lastname = $data['lastname'];

            $profiledob = $data['dateofbirth']['year'] . '-' . $data['dateofbirth']['month'] . '-' . $data['dateofbirth']['day'];
            $profile->dateofbirth = strtotime($profiledob);

            $profile->streetname = $data['streetname'];
            $profile->housenr = $data['housenr'];
            $profile->city = $data['city'];
            $profile->country = $data['country'];
            $profile->organisation = $data['organisation'];

            if ($this->Profiles->save($profile)) {
                $this->Flash->success(__('The profile has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The profile could not be saved. Please, try again.'));
        }

        $this->set(compact('profile'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $profile = $this->Profiles->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $profile = $this->Profiles->patchEntity($profile, $this->request->getData());
            if ($this->Profiles->save($profile)) {
                $this->Flash->success(__('The profile has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The profile could not be saved. Please, try again.'));
        }
        $users = $this->Profiles->Users->find('list', ['limit' => 200]);
        $this->set(compact('profile', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Profile id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $profile = $this->Profiles->get($id);
        if ($this->Profiles->delete($profile)) {
            $this->Flash->success(__('The profile has been deleted.'));
        } else {
            $this->Flash->error(__('The profile could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

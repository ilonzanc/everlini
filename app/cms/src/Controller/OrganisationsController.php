<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Organisations Controller
 *
 * @property \App\Model\Table\OrganisationsTable $Organisations
 *
 * @method \App\Model\Entity\Organisation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrganisationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $organisations = $this->paginate($this->Organisations);

        $this->set(compact('organisations'));
    }

    /**
     * View method
     *
     * @param string|null $id Organisation id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $organisation = $this->Organisations->get($id, [
            'contain' => []
        ]);

        $this->set('organisation', $organisation);
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

        $organisation = $this->Organisations->newEntity();
        if ($this->request->is('post')) {
            $organisation = $this->Organisations->patchEntity($organisation, $this->request->getData());
            $organisation->user_id = $newuserid;
            if ($this->Organisations->save($organisation)) {
                $this->Flash->success(__('The organisation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organisation could not be saved. Please, try again.'));
        }
        $this->set(compact('organisation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Organisation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $organisation = $this->Organisations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $organisation = $this->Organisations->patchEntity($organisation, $this->request->getData());
            if ($this->Organisations->save($organisation)) {
                $this->Flash->success(__('The organisation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organisation could not be saved. Please, try again.'));
        }
        $this->set(compact('organisation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Organisation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $organisation = $this->Organisations->get($id);
        if ($this->Organisations->delete($organisation)) {
            $this->Flash->success(__('The organisation has been deleted.'));
        } else {
            $this->Flash->error(__('The organisation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Interests Controller
 *
 * @property \App\Model\Table\InterestsTable $Interests
 *
 * @method \App\Model\Entity\Interest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InterestsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        /* $interests = $this->paginate($this->Interests);

        $this->set(compact('interests')); */

        $this->paginate['contain'] = ['ParentInterests'];
        $this->set('interests', $this->paginate($this->Interests));
        //$this->set('_serialize', ['interests']);

        $this->set(compact('interests'));
    }

    /**
     * View method
     *
     * @param string|null $id Interest id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $interest = $this->Interests->get($id, [
            'contain' => ['ParentInterests']
        ]);

        $parent_id = $interest->parent_interest->id;

        $descendants = $this->Interests->find('children', ['for' => $id]);

        $relatives = $this->Interests->find('children', ['for' => $parent_id]);

        $this->set(compact('interest', 'descendants', 'relatives'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $interest = $this->Interests->newEntity();
        if ($this->request->is('post')) {
            $interest = $this->Interests->patchEntity($interest, $this->request->data);
            if ($this->Interests->save($interest)) {
                $this->Flash->success(__('The interest has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The interest could not be saved. Please, try again.'));
            }
        }
        $parentInterests = $this->Interests->ParentInterests->find('list', ['limit' => 200]);
        $list = $this->Interests->find('treeList');
        $this->set(compact('interest', 'list'));
        $this->set('_serialize', ['interest']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Interest id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $interest = $this->Interests->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $interest = $this->Interests->patchEntity($interest, $this->request->getData());
            if ($this->Interests->save($interest)) {
                $this->Flash->success(__('The interest has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The interest could not be saved. Please, try again.'));
        }
        $this->set(compact('interest'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Interest id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $interest = $this->Interests->get($id);
        if ($this->Interests->delete($interest)) {
            $this->Flash->success(__('The interest has been deleted.'));
        } else {
            $this->Flash->error(__('The interest could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

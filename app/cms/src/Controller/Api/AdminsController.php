<?php
// src/Api/Controller/AdminsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

use Cake\Event\Event;

class AdminsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'add', 'edit', 'view', 'delete']);

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
        $organisations = $this->Organisations->find('all');
        $this->set([
            'organisations' => $organisations,
            '_serialize' => ['organisations']
        ]);
    }

    public function view($id)
    {

        $organisation = $this->Organisations->get($id);

        $organisation = $this->Organisations->find('all')
        ->where(['Organisations.id' => $id])
        ->contain('Users');

        $organisation = $organisation->first();

        $this->set([
            'organisation' => $organisation,
            '_serialize' => ['organisation']
        ]);
    }

    public function add() {
        $organisation = $this->Organisations->newEntity();
        if ($this->request->is('post')) {
            $organisation = $this->Organisations->patchEntity($organisation, $this->request->getData());
            $organisation->creator_id =  $this->request->data['user_id'];
            if ($this->Organisations->save($organisation)) {
                $message = 'Saved the organisation';

                $admin = $this->Admins->newEntity();
                if ($this->request->is('post')) {
                    $admin = $this->Admins->patchEntity($admin, $this->request->getData());
                    if ($this->Admins->save($admin)) {
                        $message = 'The admin has been saved.';
                    } else {
                        $message = 'The admin could not be saved. Please, try again.';
                    }
                }
            }
            else {
                $message = 'Error. Could not save the post';
            }
        }
        $this->set([
            'message' => $message,
            'organisation' => $organisation,
            '_serialize' => ['message', 'organisation']
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

    public function getOrganisationsByUser()
    {

        //return 'hey';
        $userid = $this->request->getQuery('user');

        $this->autoRender = false;
        $this->viewBuilder()->setLayout(null);

        $message = "aaaahh";

        $organisations = $this->Organisations->find('all', [
            'conditions' => ['Organisations.creator_id' => $userid]
        ]);

        $organisations = json_encode($organisations);
        $this->response->type('json');
        $this->response->body($organisations);
        return $this->response;
    }
}
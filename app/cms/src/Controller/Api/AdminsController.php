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
        $this->Auth->allow(['add', 'edit', 'view', 'delete', 'getAdminsByOrganisationId']);

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin', '*')->
            withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')->
            withHeader('Access-Control-Allow-Headers',
                       'Accept, Authorization, Cache-Control, Content-Type, X-Requested-With, x-csrf-token')->
            withHeader('Access-Control-Allow-Credentials', 'true')->
            withHeader('Access-Control-Max-Age', '3600');

        $this->response->send();
    }

    public function view($id)
    {

        $admin = $this->Admins->get($id);

        $admin = $this->Admins->find('all')
        ->where(['Admins.id' => $id])
        ->contain('Organisations');

        $admin = $admin->first();

        $this->set([
            'admin' => $admin,
            '_serialize' => ['admin']
        ]);
    }

    public function add() {
        $admin = $this->Admins->newEntity();
        if ($this->request->is('post')) {
            $data = [
                'user_id' => $this->request->data['user_id'],
                'username' => $this->request->data['username'],
                'organisations' => [
                    [
                        'id' => $this->request->data['organisation_id'],
                        '_joinData' => [
                            'main_admin' => 0,
                        ]
                    ],
                ]
            ];
            $admin = $this->Admins->newEntity($data, [
                'associated' => ['Organisations']
            ]);

            if ($this->Admins->save($admin)) {
                $message = 'The admin has been saved.';

            } else {
                $message = 'The admin could not be saved. Please, try again.';
            }
        } else {
            $message = "This isn't a POST request. Please try again.";
        }
        $this->set([
            'message' => $message,
            'admin' => $admin,
            '_serialize' => ['message', 'admin']
        ]);
    }

    public function edit($id)
    {
        $admin = $this->Admins->get($id);
        if ($this->request->is(['post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->request->getData());
            if ($this->Admins->save($admin)) {
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
        $admin = $this->Admins->get($id);
        $message = 'Deleted';
        if (!$this->Admins->delete($admin)) {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    public function getAdminsByOrganisationId()
    {
        $organisationid = $this->request->getQuery('organisation');

        $admins = $this->Admins->find()->contain('Organisations', function ($q) use ($organisationid) {
            return $q
                ->where(['Organisations.id' => $organisationid]);
        });

        $this->set([
            'admins' => $admins,
            '_serialize' => ['admins']
        ]);
    }
}
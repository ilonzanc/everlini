<?php
// src/Api/Controller/OrganisationsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

use Cake\Event\Event;

class OrganisationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'add', 'edit', 'view', 'delete', 'getOrganisationsByUser']);

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
            $data = $this->request->getData();
            $organisation = $this->Organisations->patchEntity($organisation, $this->request->getData());
            $organisation->creator_id =  $this->request->data['user_id'];

            $organisation->slug = $this->seoUrl($this->request->data['name']);

            $existingOrganisation = $this->Organisations->find('all')
            ->where(['Organisations.name' => $this->request->data['name']]);

            $organisationNumber = $existingOrganisation->count();

            if ($organisationNumber != null) {
                $message = 'This organisation already exists. Choose a different name.';
            } else {
                if ($this->Organisations->save($organisation)) {
                    $message = 'Saved the organisation';

                    $this->loadModel('Admins');

                    $existingAdmin = $this->Admins->find('all')
                    ->where(['Admins.user_id' => $this->request->data['user_id']]);

                    $adminNumber = $existingAdmin->count();

                    if ($adminNumber != 0) {
                        $organisation = $this->Organisations->get($organisation->id);
                        $admin = $this->Admins->find('all')
                        ->where(['Admins.user_id' => $this->request->data['user_id']]);
                        $admin = $admin->first();

                        $admin->_joinData = new Entity(['main_admin' => 1]);
                        $this->Organisations->Admins->link($organisation, [$admin]);
                    } else {
                        $admin = $this->Admins->newEntity();
                        if ($this->request->is('post')) {
                            $data = [
                                'user_id' => $this->request->data['user_id'],
                                'username' => $this->request->data['username'],
                                'organisations' => [
                                    [
                                        'id' => $organisation->id,
                                        '_joinData' => [
                                            'main_admin' =>1,
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
                        }
                    }

                }
                else {
                    $message = 'Error. Could not save the organisation';
                }
            }


        }
        $this->set([
            'message' => $message,
            'admin' => $admin,
            'organisationNumber' => $organisationNumber,
            '_serialize' => ['message', 'admin', 'organisationNumber']
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

    public function seoUrl($string) {
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
}
<?php
// src/Api/Controller/OrganisationsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

class OrganisationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
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
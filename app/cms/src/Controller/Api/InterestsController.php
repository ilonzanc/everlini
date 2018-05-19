<?php
// src/Api/Controller/InterestsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

class InterestsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $interests = $this->Interests->find('all');
        $this->set([
            'interests' => $interests,
            '_serialize' => ['interests']
        ]);
    }

    public function view($id)
    {
        $interest = $this->Interests->get($id);
        $this->set([
            'interest' => $interest,
            '_serialize' => ['interest']
        ]);
    }

    public function add()
    {
        $interest = $this->Interests->newEntity($this->request->getData());
        if ($this->Interests->save($interest)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'interest' => $interest,
            '_serialize' => ['message', 'interest']
        ]);
    }
}
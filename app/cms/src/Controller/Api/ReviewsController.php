<?php
// src/Api/Controller/ReviewsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

class ReviewsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $reviews = $this->Reviews->find('all');
        $this->set([
            'reviews' => $reviews,
            '_serialize' => ['reviews']
        ]);
    }

    public function view($id)
    {
        $review = $this->Reviews->get($id);
        $this->set([
            'review' => $review,
            '_serialize' => ['review']
        ]);
    }

    public function add()
    {
        $review = $this->Reviews->newEntity($this->request->getData());
        if ($this->Reviews->save($review)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'review' => $review,
            '_serialize' => ['message', 'review']
        ]);
    }

    public function edit($id)
    {
        $review = $this->Reviews->get($id);
        if ($this->request->is(['post', 'put'])) {
            $review = $this->Reviews->patchEntity($review, $this->request->getData());
            if ($this->Reviews->save($review)) {
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
        $review = $this->Reviews->get($id);
        $message = 'Deleted';
        if (!$this->Reviews->delete($review)) {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
}
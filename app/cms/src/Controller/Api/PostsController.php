<?php
// src/Api/Controller/PostsController.php
namespace App\Controller\Api;
use App\Controller\AppController;

use Cake\Event\Event;

class PostsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'edit', 'view', 'delete']);

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
        $posts = $this->Posts->find('all', [
            'conditions' => ['Posts.deleted IS NULL']
        ]);

        $this->set([
            'posts' => $posts,
            '_serialize' => ['posts']
        ]);
    }

    public function view($id)
    {
        $post = $this->Posts->get($id);
        $this->set([
            'post' => $post,
            '_serialize' => ['post']
        ]);
    }

    public function add()
    {
        $post = $this->Posts->newEntity($this->request->getData());

        if ($this->Posts->save($post)) {
            $message = 'Saved the post';
        } else {
            $message = 'Error. Could not save the post';
        }
        $this->set([
            'message' => $message,
            'post' => $post,
            '_serialize' => ['message', 'post']
        ]);
    }

    public function edit($id)
    {
        $post = $this->Posts->get($id);
        if ($this->request->is(['post', 'put'])) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $message = 'Your changes have been saved';
            } else {
                $message = 'Oops, something went wrong...';
            }
        }
        $this->set([
            'message' => $message,
            'post' => $post,
            '_serialize' => ['message', 'post']
        ]);
    }

    public function delete($id)
    {
        $message = "Can't delete post";
        $post = $this->Posts->get($id);
        $this->Posts->touch($post, 'Posts.softDelete');

        if ($this->Posts->save($post)) {
            $message = 'Deleted';
        }

        $this->set([
            'message' => $message,
            'post' => $post,
            '_serialize' => ['message', 'post']
        ]);
    }
}
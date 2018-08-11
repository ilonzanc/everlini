<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Favorites Controller
 *
 * @property \App\Model\Table\FavoritesTable $Favorites
 *
 * @method \App\Model\Entity\Favorite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FavoritesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'index', 'delete', 'getFavoritesByUserId']);

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin', '*')->
            withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')->
            withHeader('Access-Control-Allow-Headers',
                       'Accept, Authorization, Cache-Control, Content-Type, X-Requested-With, x-csrf-token')->
            withHeader('Access-Control-Allow-Credentials', 'true')->
            withHeader('Access-Control-Max-Age', '3600');

        $this->response->send();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Events');

        $eventId = $this->request->data('event_id');
        if ($this->request->data('meetup_id') != null) {
            $event = $this->Events->find()
                ->where(['meetup_id' => $this->request->data['meetup_id']]);
            $event = $event->first();
            $eventId = $event['id'];
        }

        $data = $this->request->getData();

        $favorites = $this->Favorites->find('all')
            ->where(['event_id' => $eventId, 'user_id' => $this->request->data('user_id')]);

        $this->set([
            'favorites' => $favorites,
            '_serialize' => ['favorites']
        ]);

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $favorite = $this->Favorites->newEntity();

        if ($this->request->is('post')) {
            $favorite = $this->Favorites->patchEntity($favorite, $this->request->getData());
            if ($this->Favorites->save($favorite)) {
                $message= 'Saved the event to favorites';
                $errors = $favorite->errors();
            } else {
                $message= 'Could not save the event to favorites, something went wrong';
                $errors = $favorite->errors();
            }
        } else {
            $message= 'This is not a POST request.';
        }

        $this->set([
            'favorite' => $favorite,
            'message' => $message,
            'errors' => $errors,
            '_serialize' => ['message', 'event', 'favorite', 'errors']
        ]);

    }

    /**
     * Delete method
     *
     * @param string|null $id Favorite id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->loadModel('Events');

        $eventId = $this->request->data('event_id');
        if ($this->request->data('meetup_id') != null) {
            $event = $this->Events->find()
                ->where(['meetup_id' => $this->request->data['meetup_id']]);
            $event = $event->first();
            $eventId = $event['id'];
        }

        $favorites = $this->Favorites->find('all')
            ->where(['event_id' => $eventId, 'user_id' => $this->request->data('user_id')]);

        $favorite = $favorites->first();

        $favorite = $this->Favorites->get($favorite['id']);

        if ($this->Favorites->delete($favorite)) {
            $message= 'Favorite was deleted';
            $errors = $favorite->errors();
        } else {
            $message= 'Could not delete the favorite';
            $errors = $favorite->errors();
        }

        $this->set([
            'favorite' => $favorite,
            'message' => $message,
            'errors' => $errors,
            '_serialize' => ['message', 'errors', 'favorite']
        ]);
    }

    public function getFavoritesByUserId($userid) {

        $favorites = $this->Favorites->find('all')
            ->where(['Favorites.user_id' => $userid])
            ->contain(['Users', 'Events', 'Events.Posts']);

        $favoriteEvents = [];

        foreach ($favorites as $favorite) {
            $favoriteEvents[] = $favorite->event;
        }

        $this->set([
            'favoriteEvents' => $favoriteEvents,
            '_serialize' => ['favoriteEvents']
        ]);
    }
}

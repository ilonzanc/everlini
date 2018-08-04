<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{

    public $APIkey = "AIzaSyCY30tr7QsW_ZjZRn94O2BttzRbRJNBbKM";

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $events = $this->paginate($this->Events);

        $this->set(compact('events'));
    }

    /**
     * View method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => ['Users', 'Favorites', 'Posts', 'Attachments']
        ]);

        $this->set('event', $event);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $event = $this->Events->newEntity();
        if ($this->request->is('post')) {
            $this->loadModel('Attachments');

            $newImageId = "";

            $filename = $this->request->data['submittedfile']['name'];
            $url = Router::url('/', true) . 'img/events/' . $filename;
            $uploadpath = '/img/events/';
            $uploadfile = $_SERVER['DOCUMENT_ROOT']. $uploadpath . $filename;
            if(move_uploaded_file($this->request->data['submittedfile']['tmp_name'], $uploadfile)){
                $image = $this->Attachments->newEntity();
                if ($this->request->is('post')) {
                    var_dump($image);
                    $image->name = $filename;
                    $image->path = $url;
                    if($this->Attachments->save($image)) {
                        $this->Flash->success(__('The image was saved'));
                        $newImageId = $image->id;
                    } else {
                        $this->Flash->error(__('Can not upload image. Please try again.'));
                    }
                } else {
                    $this->Flash->error(__('Can not upload image, request must be post. Please try again.'));
                }
            } else {
                $this->Flash->error(__('Could not move image. Please, try again.'));
            }

            $event->user_id =  $this->Auth->user('id');
            $event->name = $this->request->data['name'];
            $event->description = $this->request->data['description'];

            //saving the startdate
            $startdate = $this->request->data['startdate'];
            $starttime = $this->request->data['starttime'];
            $fullstartdate = $startdate . ' ' . $starttime;
            $event->startdate = strtotime($fullstartdate);

            //saving the enddate
            $enddate = $this->request->data['enddate'];
            $endtime = $this->request->data['endtime'];
            $fullenddate = $enddate . ' ' . $endtime;
            $event->enddate = strtotime($fullenddate);

            $event->address = $this->request->data['address'];

            $data = $this->getCoordinates($this->request->data['address']);

            $lat = $data['results'][0]['geometry']['location']['lat'];
            $lng = $data['results'][0]['geometry']['location']['lng'];

            $event->lat = $lat;
            $event->lng = $lng;

            $event->image_id = $newImageId;

            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The event could not be saved. Please, try again.'));
        }
        $users = $this->Events->Users->find('list', ['limit' => 200]);
        $this->set(compact('event', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $event = $this->Events->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put', 'options'])) {
            $this->loadModel('Attachments');

            $newImageId = "";

            $filename = $this->request->data['submittedfile']['name'];
            $url = Router::url('/', true) . 'img/events/' . $filename;
            $uploadpath = '/img/events/';
            $uploadfile = $_SERVER['DOCUMENT_ROOT']. $uploadpath . $filename;
            if(move_uploaded_file($this->request->data['submittedfile']['tmp_name'], $uploadfile)){
                $image = $this->Attachments->newEntity();

                    var_dump($image);
                    $image->name = $filename;
                    $image->path = $url;
                    if($this->Attachments->save($image)) {
                        $this->Flash->success(__('The image was saved'));
                        $newImageId = $image->id;
                    } else {
                        $this->Flash->error(__('Can not upload image. Please try again.'));
                    }

            } else {
                $this->Flash->error(__('Could not move image. Please, try again.'));
            }

            $event = $this->Events->patchEntity($event, $this->request->getData());
            //saving the startdate
            $startdate = $this->request->data['startdate'];
            $starttime = $this->request->data['starttime'];
            $fullstartdate = $startdate . ' ' . $starttime;
            $event->startdate = strtotime($fullstartdate);

            //saving the enddate
            $enddate = $this->request->data['enddate'];
            $endtime = $this->request->data['endtime'];
            $fullenddate = $enddate . ' ' . $endtime;
            $event->enddate = strtotime($fullenddate);

            $event->address = $this->request->data['address'];

            $data = $this->getCoordinates($this->request->data['address']);

            $lat = $data['results'][0]['geometry']['location']['lat'];
            $lng = $data['results'][0]['geometry']['location']['lng'];

            $event->lat = $lat;
            $event->lng = $lng;

            $event->image_id = $newImageId;

            if ($this->Events->save($event)) {
                $this->Flash->success(__('The event has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The event could not be saved. Please, try again.'));
        }

        $users = $this->Events->Users->find('list', ['limit' => 200]);
        $this->set(compact('event', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Event id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $event = $this->Events->get($id);
        if ($this->Events->delete($event)) {
            $this->Flash->success(__('The event has been deleted.'));
        } else {
            $this->Flash->error(__('The event could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function geoCity() {
        if ($this->request->is('ajax')) {
            $search_text = $this->request->data['search_text'];
            $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=". $search_text . "&language=nl&types=geocode&key=" . $this->APIkey;
            $response = $this->get_web_page($url);

            $result = array();
            if (isset($response) && !empty($response)) {
                foreach ($response['predictions'] as $key => $location) {
                    $result[$key]['description'] = $location['description'];
                    $result[$key]['place_id'] = $location['place_id'];
                }
            }
            $this->set(compact('result'));
        }
    }

    public function get_web_page($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response,true);
		return $response_a;
    }

    public function getCoordinates($address)
    {
        $address = str_replace(' ', '%20', $address);
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . $address . "&key=" . $this->APIkey;
        $response = $this->get_web_page($url);

        $result = array();
        if (isset($response) && !empty($response)) {
            return $response;
        }

    }
}

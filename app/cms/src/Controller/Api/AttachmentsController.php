<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Routing\Router;

use Cake\Event\Event;

/**
 * Attachments Controller
 *
 * @property \App\Model\Table\AttachmentsTable $Attachments
 *
 * @method \App\Model\Entity\Attachment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AttachmentsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'edit', 'view', 'delete']);

        //TODO: clean this up. Avoid repetitive code.

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
        $attachments = $this->paginate($this->Attachments);

        $this->set(compact('attachments'));
    }

    /**
     * View method
     *
     * @param string|null $id Attachment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $attachment = $this->Attachments->get($id, [
            'contain' => []
        ]);

        $this->set('attachment', $attachment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $newImageId = "";

        //dev
        $appRoot = "http://localhost:8765/";
        $serverRoot = $_SERVER['DOCUMENT_ROOT'];
        $uploadpath = '/img/events/';

        //prod
        /* $appRoot = "http://ilonazancaner.be/everlini/admin/";
        $serverRoot =  WWW_ROOT;
        $uploadpath = 'img/events/'; */

        $message = "een berichtje";

        $filename = $this->request->data['file']['name'];
        $url = $appRoot . 'img/events/' . $filename;
        $uploadfile = $serverRoot . $uploadpath . $filename;
        if(move_uploaded_file($this->request->data['file']['tmp_name'], $uploadfile)){
            $image = $this->Attachments->newEntity();
                $image->name = $filename;
                $image->path = $url;
                if($this->Attachments->save($image)) {
                    $message = 'The image was saved';
                    $newImageId = $image->id;
                } else {
                    $message = 'Can not upload image. Please try again.';
                }

        } else {
            $message = 'Could not move image. Please, try again.';
        }

        $this->set([
            'message' => $message,
            'image' => $image,
            '_serialize' => ['image', 'message']
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Attachment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $attachment = $this->Attachments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $attachment = $this->Attachments->patchEntity($attachment, $this->request->getData());
            if ($this->Attachments->save($attachment)) {
                $this->Flash->success(__('The attachment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The attachment could not be saved. Please, try again.'));
        }
        $this->set(compact('attachment'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Attachment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $attachment = $this->Attachments->get($id);
        if ($this->Attachments->delete($attachment)) {
            $this->Flash->success(__('The attachment has been deleted.'));
        } else {
            $this->Flash->error(__('The attachment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

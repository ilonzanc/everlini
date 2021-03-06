<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * Attachments Controller
 *
 * @property \App\Model\Table\AttachmentsTable $Attachments
 *
 * @method \App\Model\Entity\Attachment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AttachmentsController extends AppController
{

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
        if(!empty($this->request->data['submittedfile']['name'])) {
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
                    $this->Flash->error(__('Can not upload image. Please try again.'));
                }
            } else {
                $this->Flash->error(__('Could not move image. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('No file selected. Please choose a file to upload'));
        }

        $this->set(compact('attachment'));
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

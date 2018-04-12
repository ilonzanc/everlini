<?php
// src/Controller/RolesController.php

namespace App\Controller;

class RolesController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator');
        $roles = $this->Paginator->paginate($this->Roles->find());
        $this->set(compact('roles'));
    }

    public function view($id = null)
    {
        $role = $this->Roles->findById($id)->firstOrFail();
        $this->set(compact('role'));
    }

    public function add()
    {
        $role = $this->Roles->newEntity();
        if ($this->request->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->request->getData());

            if ($this->Roles->save($role)) {
                $this->Flash->success(__('Your role has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your role.'));
        }
        $this->set('role', $role);
    }

    public function edit($id)
    {
        $role = $this->Roles->findById($id)->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Roles->patchEntity($role, $this->request->getData());
            if ($this->Roles->save($role)) {
                $this->Flash->success(__('Your role has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your role.'));
        }

        $this->set('role', $role);
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $role = $this->Roles->findById($id)->firstOrFail();
        if ($this->Roles->delete($role)) {
            $this->Flash->success(__('The "{0}" role has been deleted.', $role->name));
            return $this->redirect(['action' => 'index']);
        }
    }
}
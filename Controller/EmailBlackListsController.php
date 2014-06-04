<?php
App::uses('EmailQueueAppController', 'EmailBlackList.Controller');
App::uses('EmailBlackList','EmailBlackList.Model');

/**
 * EmailBlackLists Controller
 *
 * @property EmailBlackList $EmailBlackList
 */
class EmailBlackListsController extends AppController
{
    public $components = array(
        'Paginator',
         'Filter' => array(
            'fieldMap' => array(
                'email'=>'EmailBlackList.email',
            )
        )
    );

    /**
     * index method
     *
     * @return void
     */
    public function index()
    { 
        $filter = $this->Filter->get();
        
        $conditions = $this->postConditions($filter,
            array(
               'email'=>'LIKE',
            ),
            'AND',
            true
        );

        $this->request->data = $filter;

        $this->Paginator->settings = array(
            'contain'=> array('User.Person'),
            'conditions' => $conditions,
            'order' => array('EmailBlackList.blocked_on'=>'desc'),
        );

        $this->set('dataBlackListedEmails', $this->Paginator->paginate());
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        if ($this->request->is('post')) {
            if ($this->EmailBlackList->save($this->request->data)) {
                $this->Session->setFlash(__('The email has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The email could not be saved. Please, try again.'));
                $this->render('edit');
            }
        } else {
            $this->render('edit');
        }
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        if (!$this->EmailBlackList->exists($id)) {
            throw new NotFoundException(__('Invalid email'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->EmailBlackList->save($this->request->data)) {
                $this->Session->setFlash(__('The email has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The email could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('EmailBlackList.' . $this->EmailBlackList->primaryKey => $id));
            $this->request->data = $this->EmailBlackList->find('first', $options);
        }
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null) {
        $this->EmailBlackList->id = $id;
        if (!$this->EmailBlackList->exists()) {
            throw new NotFoundException(__('Invalid email queue'));
        }
        
        //TODO: It's better to work with post request - Now it's GET.
        //$this->request->onlyAllow('post', 'delete');
        
        if ($this->EmailBlackList->delete()) {
            $this->Session->setFlash(__('Email deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Email was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}

<?php
App::uses('EmailQueueAppController', 'EmailQueue.Controller');
App::uses('EmailQueue','EmailQueue.Model');

/**
 * EmailQueues Controller
 *
 * @property EmailQueue $EmailQueue
 */
class EmailQueuesController extends EmailQueueAppController
{
    public $helpers = array(
        //'DebugKit.HtmlToolbar',
    );

    public $components = array(
        'Paginator',
         'Filter' => array(
            'fieldMap' => array(
                's'=>'EmailQueue.subject',
                'st'=>'EmailQueue.status',
                'to'=>'EmailQueue.to',
                'te'=>'EmailQueue.template',
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
               'subject'=>'LIKE',
               'template'=>'=',
            ),
            'AND',
            true
        );

        if (!empty($filter['EmailQueue']['status'])) {
            switch ($filter['EmailQueue']['status']) {
                case EmailQueue::EMAIL_STATUS_SENT:
                    $conditions['EmailQueue.sent'] = true;
                    $conditions['EmailQueue.black_listed ='] = false;
                    break;
                case EmailQueue::EMAIL_STATUS_SENDING:
                    $conditions['EmailQueue.locked'] = true;
                    $conditions['EmailQueue.black_listed ='] = false;
                    break;
                case EmailQueue::EMAIL_STATUS_ERROR:
                    $conditions['EmailQueue.send_tries >='] = 4;
                    $conditions['EmailQueue.black_listed ='] = false;
                    break;
                case EmailQueue::EMAIL_STATUS_PENDING:
                    $conditions['EmailQueue.sent'] = false;
                    $conditions['EmailQueue.locked'] = false;
                    $conditions['EmailQueue.black_listed ='] = false;
                    $conditions['EmailQueue.send_tries <'] = 4;
                    break;
                    case EmailQueue::EMAIL_STATUS_BLACK_LISTED:
                        $conditions['EmailQueue.black_listed ='] = true;
                    break;
            }
        }

        if (!empty($filter['EmailQueue']['to'])) {
            $conditions[]['OR'] = array(
                'EmailQueue.to LIKE' => '%'.$filter['EmailQueue']['to'].'%',
                'EmailQueue.to_name LIKE' => '%'.$filter['EmailQueue']['to'].'%',
            );
        }

        $this->request->data = $filter;

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'order' => array('EmailQueue.created'=>'desc'),
            'recursive' => -1,
        );

        $this->set('emailQueues', $this->Paginator->paginate());

        $templateOptions = $this->EmailQueue->find('all', array('fields'=>array('fields'=>'DISTINCT template')));
        $this->set('templateOptions', Hash::combine($templateOptions, '{n}.EmailQueue.template', '{n}.EmailQueue.template'));
    }

    public function filter()
    {
        if (!empty($this->request->data)) {
            $sfilter = $this->Filter->flatten();
            $this->request->params['named']['page'] = false;
            $this->redirect(array('action'=>'index') + Hash::filter($sfilter + $this->request->params['named']));
        } else {
            $this->request->data = $this->Filter->get();
        }
    }

    public function resetEmailStats($id)
    {
        if($this->EmailQueue->resetEmailStats($id))
        {
            $this->Session->setFlash(__('The email will be resent shortly...'));
        }
        else
        {
            $this->Session->setFlash(__('The email will not be resent shortly. Please contact tech support.'));
        }

        $this->redirect(array('action' => 'index'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        $data = $this->EmailQueue->read(null,$id);

        $configName = $data['EmailQueue']['config'];
        $template = $data['EmailQueue']['template'];
        $layout = $data['EmailQueue']['layout'];

        $email = new CakeEmail($configName);

        $email->transport('Debug')
            ->to($data['EmailQueue']['to'])
            ->subject($data['EmailQueue']['subject'])
            ->template($template, $layout)
            ->emailFormat($data['EmailQueue']['format'])
            ->viewVars($data['EmailQueue']['template_vars']);

        if (isset($data['EmailQueue']['template_vars']['language'])) {
            Configure::write('Config.language', $data['EmailQueue']['template_vars']['language']);
            Router::getRequest()->params['language'] = $data['EmailQueue']['template_vars']['language'];
        }

        $email_text = $email->send();
        $this->set(compact('email_text'));
        $this->set(compact('data'));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        if ($this->request->is('post')) {
            $this->EmailQueue->create();
            if ($this->EmailQueue->save($this->request->data)) {
                $this->Session->setFlash(__('The email queue has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The email queue could not be saved. Please, try again.'));
            }
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
        if (!$this->EmailQueue->exists($id)) {
            throw new NotFoundException(__('Invalid email queue'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->EmailQueue->save($this->request->data)) {
                $this->Session->setFlash(__('The email queue has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The email queue could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('EmailQueue.' . $this->EmailQueue->primaryKey => $id));
            $this->request->data = $this->EmailQueue->find('first', $options);
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
        $this->EmailQueue->id = $id;
        if (!$this->EmailQueue->exists()) {
            throw new NotFoundException(__('Invalid email queue'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->EmailQueue->delete()) {
            $this->Session->setFlash(__('Email queue deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Email queue was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}

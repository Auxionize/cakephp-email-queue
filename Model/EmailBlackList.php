<?php

App::uses('AppModel', 'Model');
App::uses('EmailQueue','EmailQueue.Model');

/**
 * EmailQueue model
 *
 */
class EmailBlackList extends AppModel {

/**
 * Database table used
 *
 * @var string
 * @access public
 */
   public $useTable = 'email_black_lists';
    
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array (
            'email' => array(
                    'notEmpty' => array(
                            'rule' => 'notEmpty',
                    ),
                    'validEmailRule' => array(
                            'rule' => array('email'),
                    ),
                    'uniqueEmailRule' => array(
                            'rule' => 'isUnique',
                    )
            )
            
    ) ;
   
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
            'User' => array(
                    'className' => 'User',
                    'foreignKey' => 'blocked_by',
            ),
    );
    
    
    public function beforeValidate($options = array())
    {
        $bExists = $this->exists();
    
        if (!$bExists) {
            $this->data = $this->create($this->data);
    
            /*
             * Fill owner and date on create
            */
            if (empty($this->data[$this->alias]['blocked_by'])) {
                $this->data[$this->alias]['blocked_by'] = AuthComponent::user('id');
            }
            
            if (empty($this->data[$this->alias]['blocked_date'])) {
                $this->data[$this->alias]['blocked_date'] = date('y-m-d hh:MM');
            }
        }
    
        $this->validator()->getField('email')->getRule('notEmpty')->message =
        __('Populate an email');
        $this->validator()->getField('email')->getRule('validEmailRule')->message =
        __('Email is not valid');
        $this->validator()->getField('email')->getRule('uniqueEmailRule')->message =
        __('Email is exists');
        
        return parent::beforeValidate($options);
    }
    

    public function isEmailInBlackList($email)
    {
        $blockedEmail = $this->find('first', array(
            'conditions' => array('EmailBlackList.email' => $email),
            )
        );

        if(empty($blockedEmail))
            return false;
        else {
            $EmailQueue = ClassRegistry::init('EmailQueue.EmailQueue');
            $EmailQueue->updateAll(array("black_listed"=>true),array("to"=>$email,"black_listed"=>false));
            return true;
        }
    }
    
    

}

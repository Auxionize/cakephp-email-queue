<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Auction', 'Model');
App::uses('Cache', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * @property AccessHelper $Access
 * @property AppHtmlHelper $Html
 * @property SessionHelper $Session
 *
 */
class EmailHelper extends AppHelper
{
    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Access', 'Html', 'Session');

    public function getSpanTagAsPerStatus($r)
    {
        $result = '';
        
         if ($r['EmailQueue']['sent']) : 
               $result='<span class="label label-success">'.__('sent');
            elseif ($r['EmailQueue']['locked']) : 
                $result='<span class="label label-warning">'.__('sending');
            elseif ($r['EmailQueue']['send_tries'] >= 4) : 
                $result='<span class="label label-danger">'.__('error');
            elseif ($r['EmailQueue']['black_listed']) : 
                $result='<span class="label label-danger">'.__('black listed');
            else : 
               $result='<span class="label label-default">'.__('pending');
          endif;
            
          if ($r['EmailQueue']['send_tries'] > 0) : 
                 $result.='('.$r['EmailQueue']['send_tries'].')';
          endif; 
          $result.='</span>';
        return $result;
    }
}
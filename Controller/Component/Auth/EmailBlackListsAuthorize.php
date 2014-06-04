<?php
App::uses('BusinessDomainAuthBase', 'Controller/Component/Auth');

class EmailBlackListsAuthorize extends BusinessDomainAuthBase
{
    protected function authorizeIndex($user, CakeRequest $request)
    {
        if ($user['role'] == User::ROLE_ADMIN) {
            return true;
        }
    
        return false;
    }
    
    protected function authorizeAdd($user, CakeRequest $request)
    {
        if ($user['role'] == User::ROLE_ADMIN) {
            return true;
        }
        return false;
    }
    
    protected function authorizeEdit($user, CakeRequest $request)
    {
        return $this->checkEditDelete($user, $request);
    }
    
    protected function authorizeDelete($user, CakeRequest $request)
    {
        return $this->checkEditDelete($user, $request);
    }
    
    private  function checkEditDelete($user, CakeRequest $request)
    {
        if (empty($request->params['pass'][0])) {
            return false;
        }
        
        if ($user['role'] == User::ROLE_ADMIN) {
            return true;
        }
        return false;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    private $_currentUser = null;
    
    public function getCurrentUser($force = false)
    {
        if ($this->_currentUser === null || $force === true) {
            $this->_currentUser = Auth::getUser();
        }
        
        return $this->_currentUser;
    }
}

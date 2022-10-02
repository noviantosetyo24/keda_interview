<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getUserList(Request $request)
    {
        $users = User::get();
        
        $return['users'] = $users; 
        return $this->resSuccessJson($users);
    }
}

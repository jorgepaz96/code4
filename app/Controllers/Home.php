<?php

namespace App\Controllers;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    function user() {        
        $userModel = new UserModel();
        $data = $userModel->findAll();
        print_r($data);

    }
}

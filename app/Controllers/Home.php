<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // return view('welcome_message');
        return redirect()->to('/user');
    }

    public function login()
    {
        return "DDDDD";
        return view('welcome_message');
    }
}

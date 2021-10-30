<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function index()
    {
        return "Test Controller";
        return view('welcome_message');
    }

    public function login()
    {
        return "DDDDD";
        return view('welcome_message');
    }
}
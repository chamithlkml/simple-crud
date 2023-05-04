<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('layout', array(
            'page' => 'home/index'
        ));
    }
}

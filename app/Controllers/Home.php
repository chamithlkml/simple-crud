<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Loads the home page
     *
     * @return void
     */
    public function index()
    {
        return view('layout', array(
            'page' => 'home/index'
        ));
    }
}

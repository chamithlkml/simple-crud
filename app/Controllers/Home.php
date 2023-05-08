<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Loads the home page
     *
     * @return void
     */
    public function index(): string
    {
        return view('layout', array(
            'pageHeader' => 'Home',
            'subTitle' => 'Dashboard',
            'pageDescription' => 'Your dashboard',
            'badge' => 'HOME',
            'breadcrumbs' => [
              'Home',
              'Dashboard'
            ],
            'page' => 'home/index'
        ));
    }
}

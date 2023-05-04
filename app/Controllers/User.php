<?php

namespace App\Controllers;

class User extends BaseController
{
    public function new()
    {
        return view('layout', array(
            'page' => 'users/new',
            'additionalScripts' => [
                base_url('assets/js/custom/user.js')
            ]
        ));
    }
}

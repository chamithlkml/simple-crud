<?php

namespace App\Controllers;

class User extends BaseController
{
    public function new()
    {
        return view('users/new');
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class UserControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    public function testCreatePageLoadsOk()
    {
        $result = $this->withURI('http://localhost/users/create')
        ->controller(\App\Controllers\UserController::class)
        ->execute('create');

        $this->assertTrue($result->isOK());
        $this->assertTrue($result->see('Add new user'));
    }
}

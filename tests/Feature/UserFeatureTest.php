<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;
use CodeIgniter\Test\Fabricator;
use Tests\Support\Database\Seeds\UserSeeder;

class UseFeatureTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = true;
    protected $refresh     = true;

    public function testCreateUser()
    {
        $fakeUsers = UserSeeder::generateFakeUserData(1);
        $fakeUser = $fakeUsers[0];

        $result = $this->call('post', '/users/store', $fakeUser);

        $this->assertTrue($result->isRedirect());
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url('users/list'), $url);
        $this->assertTrue($this->hasInDatabase('users', [
        'firstname' => $fakeUser['firstname'],
        'lastname' => $fakeUser['lastname'],
        'email' => $fakeUser['email'],
        'role' => 'user'
        ]));
    }
}

<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\UserSeeder;
use App\Models\UserModel;
use CodeIgniter\Test\Fabricator;

final class UserDatabaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed = UserSeeder::class;

    public function testUserFindAll()
    {
        $userModel = new UserModel();

        //Get every row created by UserSeeder
        $objects = $userModel->findAll();

        // Make sure the count is as expected
        $this->assertCount(10, $objects);
    }

    public function testSoftDeleteLeavesRow()
    {
        $userModel = new UserModel();

        /** @var stdClass $object */
        $firstUser = $userModel->first();
        $userModel->delete($firstUser['id']);

        // The model should no longer find it
        $this->assertNull($userModel->find($firstUser['id']));

        // ... but it should still be in the database
        $result = $userModel->builder()->where('id', $firstUser['id'])->get()->getResult();

        $this->assertCount(1, $result);
    }
}

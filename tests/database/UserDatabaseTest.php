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

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;

    // For Seeds
    protected $seedOnce = true;
    protected $seed = UserSeeder::class;

    /**
     * Manually seed data and check for user count
     *
     * @return void
     */
    public function testUserCount(): void
    {
        $userModel = new UserModel();

        //Get every row created by UserSeeder
        $beforeAddingCount = count($userModel->findAll());

        $fakeUsers = UserSeeder::generateFakeUserData(10);

        foreach ($fakeUsers as $fakeUser) {
            $userModel->insert($fakeUser);
        }

        $afterAddingCount = count($userModel->findAll());

        // Make sure the count is as expected
        $this->assertEquals(($beforeAddingCount + 10), $afterAddingCount);
    }

    /**
     * Tests whether soft deletes leaves the particular row
     *
     * @return void
     */
    public function testSoftDeleteLeavesRow(): void
    {
        $userModel = new UserModel();

        $fakeUsers = UserSeeder::generateFakeUserData(1);

        foreach ($fakeUsers as $fakeUser) {
            $userModel->insert($fakeUser);
        }

        $users = $userModel->findAll();
        $firstUser = $users[0];
        $userModel->delete($firstUser['id']);

        // The model should no longer find it
        $this->assertNull($userModel->find($firstUser['id']));

        // ... but it should still be in the database
        $result = $userModel->builder()->where('id', $firstUser['id'])->get()->getResult();

        $this->assertCount(1, $result);
    }
}

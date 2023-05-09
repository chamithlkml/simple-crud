<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\UserModel;

class User extends Seeder
{
    /**
     * Run User seeds
     *
     * @return void
     */
    public function run(): void
    {
        $fakeUsers = $this->generateFakeUserData(20);

        $builder = $this->db->table('users');

        foreach ($fakeUsers as $fakeUser) {
            $builder->insert($fakeUser);
        }
    }

    /**
     * Generate fake user data
     *
     * @param integer $count
     * @return array
     */
    private function generateFakeUserData(int $count = 1): array
    {
        helper('text');

        $userFabricator = new Fabricator(UserModel::class, [
          'firstname' => 'firstName',
          'lastname' => 'lastName',
          'email' => 'email',
          'password' => 'password'
        ]);

        $fakeUsers = $userFabricator->make($count);

      // Adding a random username and a mobile number with 11 digits
        $fakeUsers = array_map(function ($fakeUser) {
            $fakeUser['username'] = 'username-' . rand(0, time()) . '-' . rand(0, time());
            $fakeUser['mobile'] = random_string('numeric', 11);
            $fakeUser['role'] = 'user';

            return $fakeUser;
        }, $fakeUsers);

        return $fakeUsers;
    }
}

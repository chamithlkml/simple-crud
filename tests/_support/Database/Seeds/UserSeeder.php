<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {

        $fakeUsers = self::generateFakeUserData(10);

        $builder = $this->db->table('users');

        foreach ($fakeUsers as $fakeUser) {
            $builder->insert($fakeUser);
        }
    }

    public static function generateFakeUserData(int $count = 1): array
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

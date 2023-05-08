<?php

namespace Tests\Support\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey     = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields  = [
    'firstname',
    'lastname',
    'email',
    'mobile',
    'username',
    'password',
    'role'
    ];
    protected $validationRules    = [
    'firstname' => 'required|min_length[1]|max_length[64]',
    'lastname' => 'required|min_length[1]|max_length[64]',
    'email' => 'required|valid_email',
    'mobile' => 'required|min_length[11]|max_length[11]',
    'username' => 'required|min_length[6]|is_unique[users.username]',
    'password' => 'required|min_length[6]',
    'role' => 'required'
    ];
    protected $skipValidation     = false;

    /**
     * Set salt if it's not set(when inserting), and hash the password with the salt.
     *
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data): array
    {

        if (! isset($data['data']['password'])) {
            return $data;
        }

        if (! isset($data['data']['salt'])) {
            $data['data']['salt'] = password_hash(random_bytes(32), PASSWORD_BCRYPT);
        }

        $data['data']['password'] = password_hash($data['data']['password'] . $data['data']['salt'], PASSWORD_BCRYPT);

        return $data;
    }

    /**
     * Adding a timestamp to the end of the username of deleting user just to allow adding
     * another user with the same username
     *
     * @param [type] $data
     * @return array
     */
    protected function addTimestampToUsername($data): array
    {
        $usernameData = $this->select('username')
            ->where('id', $data['id'][0])
            ->get()->getRowArray();

        $usernameData['username'] = $usernameData['username'] . '-' . time();
        $this->update($data['id'][0], $usernameData);

        return $data;
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];
    protected $beforeDelete   = ['addTimestampToUsername'];
}

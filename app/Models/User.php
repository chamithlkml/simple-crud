<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['firstname', 'lastname', 'email', 'mobile', 'username', 'password'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'firstname' => 'required|alpha_numeric|trim',
        'lastname' => 'required|alpha_numeric|trim',
        'email' => 'required|valid_email|is_unique[users.email]',
        'mobile' => 'required|min_length[11]|trim',
        'username' => 'required|alpha_numeric',
        'password' => 'required|alpha_numeric|min_length[6]',
        'salt' => 'required',
        'role' => 'required'
    ];

    /**
     * Set salt if it's not set(when inserting), and hash the password with the salt.
     *
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data): array
    {
        if(! isset($data['data']['password'])){
            return $data;
        }

        if(! isset($data['data']['salt'])){
            $data['data']['salt'] = password_hash(random_bytes(32), PASSWORD_BCRYPT);
        }

        $data['data']['password'] = password_hash($data['data']['password'] . $data['data']['salt'], PASSWORD_BCRYPT);

        return $data;
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];
}

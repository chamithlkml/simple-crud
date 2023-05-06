<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['firstname', 'lastname', 'email', 'mobile', 'username', 'password', 'role', 'salt'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    public $validationRules = [
        'firstname' => 'required|min_length[1]|max_length[64]',
        'lastname' => 'required|min_length[1]|max_length[64]',
        'email' => 'required|valid_email',
        'mobile' => 'required|min_length[11]|max_length[11]',
        'username' => 'required|min_length[6]|is_unique[users.username]',
        'password' => 'required|min_length[6]',
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

<?php

namespace App\Libraries;

use App\Models\UserModel;

class UserLibrary
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

  /**
   * Return an array of validation errors
   *
   * @param [type] $data
   * @param array $validatingFields
   * @return array
   */
    public function getValidationErrors($data, $validatingFields = []): array
    {
        $validation =  \Config\Services::validation();
        $checkRules = [];

      # Filter out rules for $validatingFields only
        foreach ($validatingFields as $key) {
            if (isset($this->userModel->validationRules[$key])) {
                $checkRules[$key] = $this->userModel->validationRules[$key];
            }
        }

        $validation->setRules($checkRules);

        $validationErrors = [];

        if (! $validation->run($data)) {
            $validationErrors = $validation->getErrors();
        }

        return $validationErrors;
    }

  /**
   * Insert or Update user data depending on the existance of id field
   *
   * @param array $data
   * @return integer
   */
    public function saveUser(array $data): int
    {
        return $this->userModel->save($data);
    }

  /**
   * Return user object searched by id. Null is returned if not found
   *
   * @param integer $id
   */
    public function getUserById(int $id)
    {
        return $this->userModel
            ->select('id, firstname, lastname, email, mobile, username')
            ->where('role', 'user')
            ->where('id', $id)
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();
    }

  /**
   * Delete user object
   *
   * @param integer $id
   * @return void
   */
    public function deleteUser(int $id): void
    {
        $this->userModel->delete($id);
    }

    /**
     * Returns the data object expected by the Datatables ajax call
     *
     * @param integer $limit
     * @param integer $offset
     * @param string $searchTerm
     * @param integer $draw
     * @param integer $orderColumnIndex
     * @param string $orderDirection
     * @return array
     */
    public function getDataTableResponse(
        int $limit = 10,
        int $offset = 0,
        string $searchTerm = '',
        int $draw = 1,
        int $orderColumnIndex = 0,
        string $orderDirection = 'asc'
    ): array {
      # Retrieve total number of users in a separate builder
        $recordsTotalRow = $this->userModel->db->table('users')
                        ->selectCount('*', 'num')
                        ->where('deleted_at IS NULL')
                        ->get()->getRowArray();

        $recordsFilteredRow = $recordsTotalRow;

      // main query builder
        $builder = $this->userModel->db->table('users')
                ->select(
                    'id, firstname, lastname, email, mobile, username'
                );

        if ($searchTerm != '') {
            $builder->groupStart();
              $builder->like('firstname', $searchTerm);
              $builder->orLike('lastname', $searchTerm);
              $builder->orLike('email', $searchTerm);
              $builder->orLike('username', $searchTerm);
              $builder->orLike('mobile', $searchTerm);
            $builder->groupEnd();

          # Retrieving filtered count in a separate builder
            $recordsFilteredRow = $this->userModel->db->table('users')
                                ->groupStart()
                                  ->like('firstname', $searchTerm)
                                  ->orLike('lastname', $searchTerm)
                                  ->orLike('email', $searchTerm)
                                  ->orLike('username', $searchTerm)
                                  ->orLike('mobile', $searchTerm)
                                ->groupEnd()
                                ->where('deleted_at IS NULL')
                                ->selectCount('*', 'num')
                                ->get()->getRowArray();
        }

      # columns shown in the Datatable following the same order from left to right
        $columns = ['firstname', 'lastname', 'email', 'mobile', 'username'];

        $orderColumn = $columns[$orderColumnIndex];

        if (isset($orderColumn)) {
            $builder->orderBy($orderColumn, strtoupper($orderDirection));
        }

        $builder->limit($limit, $offset);
        $builder->where('deleted_at is NULL');

        $users = $builder->get()->getResult();

        foreach ($users as $user) {
            $user->action = $this->getActionButtons($user->id);
            unset($user->id);
        }

        $response = [
        'draw' => $draw,
        'recordsTotal' => $recordsTotalRow['num'],
        'recordsFiltered' => $recordsFilteredRow['num'],
        'data' => $users
        ];

        return $response;
    }

    private function getActionButtons(int $id): string
    {
        return '<a href="/users/' . $id . '/update">' .
            form_button('edit', 'Edit', ['class' => 'btn btn-outline-primary btn-sm']) .
          '</a>' .
          form_button(
              'edit',
              'Delete',
              ['class' => 'btn btn-outline-danger btn-sm delete-user-btn', 'data-user-id' => $id]
          );
    }
}

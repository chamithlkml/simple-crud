<?php

namespace App\Libraries;

use App\Models\UserModel;

class UserLibrary{
  protected $userModel;

  public function __construct()
  {
      $this->userModel = new UserModel();
  }

  public function getDataTableResponse(int $limit = 10, int $offset = 0, string $searchTerm = '',int $draw = 1): array
  {
    # Retrieve total number of users in a separate builder
    $recordsTotalRow = $this->userModel->db->table('users')
                        ->selectCount('*', 'num')
                        ->get()->getRowArray();

    $recordsFilteredRow = $recordsTotalRow;

    // main query builder
    $builder = $this->userModel->builder()
                ->select(
                  'id, firstname, lastname, email, mobile, username'
                );

    if($searchTerm != ''){
      $builder->like('firstname', $searchTerm);
      $builder->orLike('lastname', $searchTerm);

      # Retrieving filtered count in a separate builder
      $recordsFilteredRow = $this->userModel->db->table('users')
                                ->like('firstname', $searchTerm)
                                ->orLike('lastname', $searchTerm)
                                ->selectCount('*', 'num')
                                ->get()->getRowArray();

    }

    $builder->limit($limit, $offset);
    $users = $builder->get()->getResult();

    foreach($users as $user){
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
    return '<a href="/users/update/'. $id .'">' . form_button('edit', 'Edit', ['class' => 'btn btn-outline-primary btn-sm']) .'</a>' .
      form_button('edit', 'Delete', ['class' => 'btn btn-outline-danger btn-sm delete-user-btn', 'data-user-id' => $id]);
  }

}
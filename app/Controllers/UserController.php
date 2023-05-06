<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\UserLibrary;

class UserController extends BaseController
{
    protected $helpers = ['form'];

    public function index(): object
    {
      log_message('info', print_r($_GET, true));

      $search = $this->request->getVar('search');
      $searchTerm = is_null($search) ? '' : $search['value'];

      $userLibrary = new UserLibrary();
      $dataTablesResponse = $userLibrary->getDataTableResponse(
        intval($this->request->getVar('length')),
        intval($this->request->getVar('start')),
        $searchTerm,
        intval($this->request->getVar('draw'))
      );

      log_message('info', print_r($dataTablesResponse, true));

      return $this->response->setJSON($dataTablesResponse);
    }

    public function list(): string
    {
      return view('layout', array(
          'page' => 'users/list',
          'additionalStylesheets' => [
              base_url('assets/css/jquery.dataTables.css')
          ],
          'additionalScripts' => [
              base_url('assets/js/jquery.dataTables.js'),
              base_url('assets/js/custom/user.js')
          ]
      ));
    }

    public function create(): string
    {
        return view('layout', array(
            'page' => 'users/create',
            'action' => base_url('users/store'),
            'formId' => 'userForm',
            'data' => array()
        ));
    }

    public function store()
    {

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password')
        ];

        $rules = [
            'firstname' => 'required|min_length[1]|max_length[64]',
            'lastname' => 'required|min_length[1]|max_length[64]',
            'email' => 'required|valid_email',
            'mobile' => 'required|min_length[11]|max_length[11]',
            'username' => 'required|min_length[6]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validateData($data, $rules)) {

            unset($data['password']);

            return view('layout', array(
                'page' => 'users/create',
                'action' => 'users/store',
                'formId' => 'userForm',
                'errors' => $this->validator->getErrors(),
                'data' => $data
            ));
        } else {

            # Set role as user allowing the app to add `admin` in a different
            # controller or in a seeding
            $data['role'] = 'user';
            $userModel = new UserModel();
            $savedUserId = $userModel->insert($data);
            
            if(is_int($savedUserId)){
                $session = session();
                $session->setFlashdata('success', 'User added successfully');

                return redirect()->to(base_url('users/list'));
            }
        }
    }
}

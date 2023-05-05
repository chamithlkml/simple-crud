<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $helpers = ['form'];

    public function index(){
        return view('layout', array(
            'page' => 'users/index'
        ));
    }

    public function create()
    {

        return view('layout', array(
            'page' => 'users/create',
            'action' => 'users/store',
            'formId' => 'userForm',
            'data' => array(),
            'additionalScripts' => [
                base_url('assets/js/custom/user.js')
            ]
        ));
    }

    public function store(){

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
                'data' => $data,
                'additionalScripts' => [
                    base_url('assets/js/custom/user.js')
                ]
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

                return redirect()->to('users');
            }
        }
    }
}

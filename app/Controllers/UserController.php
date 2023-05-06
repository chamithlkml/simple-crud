<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\UserLibrary;

class UserController extends BaseController
{
    protected $helpers = ['form'];

    public function index(): object
    {
      $search = $this->request->getVar('search');
      $searchTerm = is_null($search) ? '' : $search['value'];

      $userLibrary = new UserLibrary();
      $orderDetails = $this->request->getVar('order');

      $dataTablesResponse = $userLibrary->getDataTableResponse(
        intval($this->request->getVar('length')),
        intval($this->request->getVar('start')),
        $searchTerm,
        intval($this->request->getVar('draw')),
        $orderDetails[0]['column'],
        $orderDetails[0]['dir']
      );

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
        $userLibrary = new UserLibrary();
        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role' => 'user' # Set role as user expecting the app may have a higher role like admin
        ];

        $validationErrors = $userLibrary->getValidationErrors($data, array_keys($data));

        if (count($validationErrors) > 0) {

            unset($data['password']);

            return view('layout', array(
                'page' => 'users/create',
                'action' => 'users/store',
                'formId' => 'userForm',
                'errors' => $validationErrors,
                'data' => $data
            ));
        } else {
            $savedUserId = $userLibrary->saveUser($data);
            $session = session();
            
            if(is_int($savedUserId)){
                $session->setFlashdata('success', 'User added successfully');

                return redirect()->to(base_url('users/list'));
            }else{
                $session->setFlashdata('error', 'User not inserted. Please try again.');

                return redirect()->to(base_url('users/create'));
            }
        }
    }

    public function delete($id)
    {
        $userLibrary = new UserLibrary();
        $foundUser = $userLibrary->getUserById($id);
        $session = session();

        if($foundUser){
            $userLibrary->deleteUser($id);
            
            $deletedUser = $userLibrary->getUserById($id);

            if(! isset($deletedUser)){
                $session->setFlashdata('success', 'User is deleted successfully');
            }else{
                $session->setFlashdata('error', 'User not deleted. Please try again.');
            }
        }else{
            $session->setFlashdata('error', 'User not found. Please try again.');
        }

        return redirect()->to(base_url('users/list'));
    }

    public function update($id)
    {
        $userLibrary = new UserLibrary();
        $foundUser = $userLibrary->getUserById($id);
        $session = session();

        if(! isset($foundUser)){
            $session->setFlashdata('error', 'User not found. Please try again.');
            
            return redirect()->to(base_url('users/list'));
          }else{
            return view('layout', array(
                'page' => 'users/update',
                'action' => base_url("users/{$id}/put"),
                'formId' => 'userForm',
                'data' => $foundUser
            ));
        }
    }

    public function put($id)
    {
      $userLibrary = new UserLibrary();
      $session = session();
      $foundUser = $userLibrary->getUserById($id);

      if(! isset($foundUser)){
        $session->setFlashdata('error', 'User not found. Please try again.');
        return redirect()->to(base_url('users/list'));
      }else{
        $data = [
          'firstname' => $this->request->getPost('firstname'),
          'lastname' => $this->request->getPost('lastname'),
          'email' => $this->request->getPost('email'),
          'mobile' => $this->request->getPost('mobile'),
          'username' => $this->request->getPost('username'),
          'password' => $this->request->getPost('password')
        ];

        # Since username field is validated for uniqueness
        if($foundUser['username'] == $data['username']){
          unset($data['username']);
        }

        $validationErrors = $userLibrary->getValidationErrors($data, array_keys($data));

        if(count($validationErrors) > 0){
          unset($data['password']);

          return view('layout', array(
                'page' => 'users/update',
                'action' => base_url("users/{$id}/put"),
                'formId' => 'userForm',
                'errors' => $validationErrors,
                'data' => $data
          ));
        }else{
          $data['id'] = $id;
          $userLibrary->saveUser($data);
          $session->setFlashdata('success', 'User updated successfully');
          
          return redirect()->to(base_url('users/list'));
        }

      }
    }
}

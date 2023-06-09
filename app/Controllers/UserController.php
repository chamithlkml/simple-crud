<?php

namespace App\Controllers;

use App\Libraries\UserLibrary;
use CodeIgniter\Database\Exceptions\DatabaseException;

class UserController extends BaseController
{
    protected $helpers = ['form'];

    /**
     * Returns the Json object required for users Datatable
     *
     * @return object
     */
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

    /**
     * Loads the users list page
     *
     * @return string
     */
    public function list(): string
    {
        return view('layout', array(
          'pageHeader' => 'Admin',
          'subTitle' => 'Lists users',
          'pageDescription' => 'See users with pagination',
          'badge' => 'USERS',
          'breadcrumbs' => [
            'User',
            'List'
          ],
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

    /**
     * Loads the user create form
     *
     * @return string
     */
    public function create(): string
    {
        return view('layout', array(
            'pageHeader' => 'Admin',
            'subTitle' => 'Create a new user',
            'pageDescription' => 'Fill the following form to create a new user',
            'badge' => 'NEW USER',
            'breadcrumbs' => [
              'User',
              'New'
            ],
            'page' => 'users/create',
            'action' => base_url('users/store'),
            'formId' => 'userForm',
            'data' => array()
        ));
    }

    /**
     * Insert new user object
     *
     * @return void
     */
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
                'pageHeader' => 'Admin',
                'subTitle' => 'Create a new user',
                'pageDescription' => 'Fill the following form to create a new user',
                'badge' => 'NEW USER',
                'breadcrumbs' => [
                  'User',
                  'New'
                ],
                'page' => 'users/create',
                'action' => 'users/store',
                'formId' => 'userForm',
                'errors' => $validationErrors,
                'data' => $data
            ));
        } else {
            try {
                $savedUserId = $userLibrary->saveUser($data);

                $session = session();

                if (is_int($savedUserId)) {
                    $session->setFlashdata('success', 'User added successfully');

                    return redirect()->to(base_url('users/list'));
                } else {
                    $session->setFlashdata('error', 'User not inserted. Please try again.');

                    return redirect()->to(base_url('users/create'));
                }
            } catch (DatabaseException $e) {
                $session->setFlashdata('error', $e->getMessage());

                return redirect()->to(base_url('users/create'));
            }
        }
    }

    /**
     * Delete a given user if exists
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $userLibrary = new UserLibrary();
        $foundUser = $userLibrary->getUserById($id);
        $session = session();

        if ($foundUser) {
            try {
                $userLibrary->deleteUser($id);

                $deletedUser = $userLibrary->getUserById($id);

                if (! isset($deletedUser)) {
                    $session->setFlashdata('success', 'User is deleted successfully');
                } else {
                    $session->setFlashdata('error', 'User not deleted. Please try again.');
                }
            } catch (DatabaseException $e) {
                $session->setFlashdata('error', $e->getMessage());
            }
        } else {
            $session->setFlashdata('error', 'User not found. Please try again.');
        }

        return redirect()->to(base_url('users/list'));
    }

    /**
     * Loading the update user page
     *
     * @param [type] $id
     * @return void
     */
    public function update($id)
    {
        $userLibrary = new UserLibrary();
        $foundUser = $userLibrary->getUserById($id);
        $session = session();

        if (! isset($foundUser)) {
            $session->setFlashdata('error', 'User not found. Please try again.');

            return redirect()->to(base_url('users/list'));
        } else {
            return view('layout', array(
                'pageHeader' => 'Admin',
                'subTitle' => 'Updates a user',
                'pageDescription' => 'Fill the following form to update the user',
                'badge' => 'UPDATE USER',
                'breadcrumbs' => [
                  'User',
                  'Update'
                ],
                'page' => 'users/update',
                'action' => base_url("users/{$id}/put"),
                'formId' => 'userForm',
                'data' => $foundUser
            ));
        }
    }

    /**
     * Update a given user with provided data
     *
     * @param [type] $id
     * @return void
     */
    public function put($id)
    {
        $userLibrary = new UserLibrary();
        $session = session();
        $foundUser = $userLibrary->getUserById($id);

        if (! isset($foundUser)) {
            $session->setFlashdata('error', 'User not found. Please try again.');
            return redirect()->to(base_url('users/list'));
        } else {
            $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password')
            ];

          # Since username field is validated for uniqueness
            if ($foundUser['username'] == $data['username']) {
                unset($data['username']);
            }

            $validationErrors = $userLibrary->getValidationErrors($data, array_keys($data));

            if (count($validationErrors) > 0) {
                unset($data['password']);

                return view('layout', array(
                'pageHeader' => 'Admin',
                'subTitle' => 'Updates a user',
                'pageDescription' => 'Fill the following form to update the user',
                'badge' => 'UPDATE USER',
                'breadcrumbs' => [
                  'User',
                  'Update'
                ],
                'page' => 'users/update',
                'action' => base_url("users/{$id}/put"),
                'formId' => 'userForm',
                'errors' => $validationErrors,
                'data' => $data
                ));
            } else {
                $data['id'] = $id;

                try {
                    $userLibrary->saveUser($data);
                    $session->setFlashdata('success', 'User updated successfully');

                    return redirect()->to(base_url('users/list'));
                } catch (DatabaseException $e) {
                    $session->setFlashdata('error', $e->getMessage());
                    return redirect()->to(base_url('users/list'));
                }
            }
        }
    }
}

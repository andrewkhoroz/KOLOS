<?php

class UserController extends Zend_Controller_Action {

    private $_options;
    private $_formOptions;

    /**
     * Init
     * 
     * @see Zend_Controller_Action::init()
     */
    public function init() {

        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
        $this->_formOptions = $opt = array(
            'custom' => array(
                'timeout' => $this->_options['auth']['timeout']
            )
        );
    }

    /**
     * Checks if user has identity (is logged in)
     */
    public function indexAction() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->identity = $auth->getIdentity();
        }
    }

    public function createAction() {
        $userForm = new Form_UserForm($this->_formOptions);
        $userForm->removeElement('token');
        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $mdlUser = new Model_Users();
                $mdlUser->createUser(
                        $userForm->getValue('username'), $userForm->getValue('password'), $userForm->getValue('first_name'), $userForm->getValue('last_name'), $userForm->getValue('role_id')
                );
                return $this->_forward('list');
            }
        }
        $userForm->setAction('/user/create');
        $this->view->form = $userForm;
    }

    public function listAction() {
        $currentUsers = Model_Users::getUsers();
        if ($currentUsers->count() > 0) {
            $this->view->users = $currentUsers;
        } else {
            $this->view->users = null;
        }
    }

    public function updateAction() {
        $userForm = new Form_UserForm($this->_formOptions);
        $userForm->setAction('/user/update');
        $userForm->removeElement('password');
        $userForm->removeElement('token');
        $mdlUseruserModel = new Model_Users();
        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $mdlUseruserModel->updateUser(
                        $userForm->getValue('id'), $userForm->getValue('username'), $userForm->getValue('first_name'), $userForm->getValue('last_name'), $userForm->getValue('role_id')
                );
                return $this->_forward('list');
            }
        } else {
            $id = $this->_request->getParam('id');
            $currentUser = $mdlUseruserModel->find($id)->current();
            $userForm->populate($currentUser->toArray());
        }
        $this->view->form = $userForm;
    }

    public function passwordAction() {
        $passwordForm = new Form_UserForm($this->_formOptions);
        $passwordForm->setAction('/user/password');
        $passwordForm->removeElement('first_name');
        $passwordForm->removeElement('last_name');
        $passwordForm->removeElement('username');
        $passwordForm->removeElement('role_id');
        $passwordForm->removeElement('token');
        $userModel = new Model_Users();
        if ($this->_request->isPost()) {
            if ($passwordForm->isValid($_POST)) {
                $userModel->updatePassword(
                        $passwordForm->getValue('id'), $passwordForm->getValue('password')
                );
                return $this->_forward('list');
            }
        } else {
            $id = $this->_request->getParam('id');
            $currentUser = $userModel->find($id)->current();
            $passwordForm->populate($currentUser->toArray());
        }
        $this->view->form = $passwordForm;
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id');
        $mdlUseruserModel = new Model_Users();
        $mdlUseruserModel->deleteUser($id);
        return $this->_forward('list');
    }

    public function loginAction() {
        $data = array();
        $data['is_logged'] = false;
        $loginForm = new Form_LoginForm($this->_formOptions);
        if ($this->_request->isPost() && $loginForm->isValid($_POST)) {
            $data = $loginForm->getValues();
            //set up the auth adapter
            // get the default db adapter
            $db = Zend_Db_Table::getDefaultAdapter();
            //create the auth adapter
            $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users',
                            'username', 'password');
            //set the username and password
            $authAdapter->setIdentity($data['username']);
            $authAdapter->setCredential(md5($data['password']));
            //authenticate
            $result = $authAdapter->authenticate();
            if ($result->isValid()) {
                $data['is_logged'] = true;
                // store the username, first and last names of the user
                $auth = Zend_Auth::getInstance();
                $storage = $auth->getStorage();
                $storage->write($authAdapter->getResultRowObject(
                                array('id', 'username', 'first_name', 'last_name', 'role_id')));
                            $this->_redirect('/');
            return;
            } else {
                $this->view->loginMessage = "Sorry, your username or
                password was incorrect";
            }
        }
        $this->view->form = $loginForm;
        
    }

    public function logoutAction() {
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
    }

}


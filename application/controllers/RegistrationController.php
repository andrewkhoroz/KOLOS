<?php

/**
 * Description of RegistrationController
 *
 * @author KHOROZ
 */
class RegistrationController extends Zend_Controller_Action {

    /**
     * This action is the home page of the website
     *
     */
    public function indexAction() {
        $registrationForm = new Form_RegistrationForm();
        if ($this->_request->isPost()) {
            if ($registrationForm->isValid($_POST)) {
                $mdlUser = new Model_Users();
                $mdlUser->createUser(
                        $registrationForm->getValue('username'), $registrationForm->getValue('password'), $registrationForm->getValue('first_name'), $registrationForm->getValue('last_name'), $registrationForm->getValue('role_id')
                );
                return $this->_forward('/');
            }
        }
        $registrationForm->setAction('/registration/');
        $this->view->form = $registrationForm;
    }

}

?>

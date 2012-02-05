<?php

class Form_RegistrationForm extends Zend_Form {

    public function __construct() {
        parent::__construct();
    }

    public function init() {
        $this->setMethod('post');
        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

        //create the form elements
        $username = $this->createElement('text', 'username');
        $username->addValidator('alnum')
                ->addValidator('regex', false, array('/^[a-z]+/'))
                ->addValidator('stringLength', false, array(6, 20))
                ->setRequired(true)
                ->addFilter('StringToLower')
                ->addFilter('StripTags')
                ->setLabel('Логін: ');
        $this->addElement($username);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Пароль: ')
                ->setRequired('true')
                ->addValidator('StringLength', false, array(6))
                ->setRequired(true);
        $this->addElement($password);



        $firstName = $this->createElement('text', 'first_name');
        $firstName->setLabel('Ім`я: ');
        $firstName->setRequired('true');
        $firstName->addFilter('StripTags');
        $this->addElement($firstName);

        $lastName = $this->createElement('text', 'last_name');
        $lastName->setLabel('Прізвище: ');
        $lastName->setRequired('true');
        $lastName->addFilter('StripTags');
        $this->addElement($lastName);

        $this->addAttribs(array("id" => "registration-form"));
        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    }

}

?>

<?php

class Form_UserForm extends Zend_Form {

    private $_timeout;

    public function __construct($options=null) {
        if (is_array($options)) {
            if (!empty($options['custom'])) {
                if (!empty($options['custom']['timeout'])) {
                    $this->_timeout = $options['custom']['timeout'];
                }
                unset($options['custom']);
            }
        }
        parent::__construct($options);
    }

    public function init() {
        $this->addElement('hash', 'token', array(
            'timeout' => $this->_timeout
        ));
        $roleModels = new Model_Roles();
        $allRoles = $roleModels->getRoles();
//        Zend_Debug::dump($allRoles,'$allRoles');

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

        $roleElement = $this->createElement('select', 'role_id');
        $roleElement->setLabel("Виберіть роль:");
        $roleElement->setRequired(true);

        foreach ($allRoles as $role) {
            $roleElement->addMultiOption($role->id, $role->role);
        }
        $this->addElement($roleElement);

        $this->addAttribs(array("id" => "user-form"));
        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    }

}

?>

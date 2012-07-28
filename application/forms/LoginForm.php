<?php

/**
 * 
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class Form_LoginForm extends Zend_Form {

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
//        $this->addElement('hash', 'token', array(
//            'timeout' => $this->_timeout
//        ));

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
        $this->addElement('submit', 'submit', array(
            'label' => 'Увійти'
        ));
    }

}
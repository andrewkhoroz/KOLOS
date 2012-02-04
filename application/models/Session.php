<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_Session
 *
 * @author ANDREW
 */
class Model_Session {
    const SESSION_NAMESPACE='Zend_Auth';

    public static function saveToSession($key, $value) {
        $session = new Zend_Session_Namespace(self::SESSION_NAMESPACE);
        $session->${$key} = $value;
        return true;
    }

    public static function getFromSession($key) {
        $session = new Zend_Session_Namespace(self::SESSION_NAMESPACE);
        return $session->${$key};
    }
    
    public static  function getSession(){
        $session = new Zend_Session_Namespace(self::SESSION_NAMESPACE);
        return $session;
    }

}

?>

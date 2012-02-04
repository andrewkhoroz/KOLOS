<?php

/**
 * Description of CssHelper
 *
 * @author KHOROZ
 */
class App_View_Helper_CssHelper extends Zend_View_Helper_Abstract {

    function cssHelper() {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $file_uri = 'public/css/' . $request->getControllerName() . '/' . $request->getActionName() . '.css';

        if (file_exists($file_uri)) {
            $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/' . $file_uri);
        }

        return $this->view->headLink();
    }

}
?>

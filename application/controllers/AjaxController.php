<?php

/**
 * Description of AjaxController
 *
 * @author ANDREW
 */
class AjaxController extends Zend_Controller_Action {

    public function init() {
        //Disable view rendering
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();
    }

    public function logErrorResponse() {
        $request = $this->getRequest();
        $params = $request->getParams();

        var_dump($params, '$params');
        die();
        Zend_Debug::ajaxDump($params, 'error response');
    }


}

?>

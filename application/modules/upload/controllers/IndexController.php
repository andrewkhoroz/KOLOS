<?php

class Upload_IndexController extends Zend_Controller_Action {

    public function preDispatch() {
        
    }

    public function init() {
        
    }

    public function indexAction() {
        
    }

    public function processAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();
        $upload_handler = new App_UploadHandler();

        header('Pragma: no-cache');
        header('Cache-Control: private, no-cache');
        header('Content-Disposition: inline; filename="files.json"');

//        Zend_Debug::fdump($_SERVER['REQUEST_METHOD'],'REQUEST_METHOD');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'HEAD':
            case 'GET':
                $upload_handler->get();
                break;
            case 'POST':
                $upload_handler->post();
                break;
            case 'DELETE':
                $upload_handler->delete();
                break;
            default:
                header('HTTP/1.0 405 Method Not Allowed');
        }
    }

}

?>

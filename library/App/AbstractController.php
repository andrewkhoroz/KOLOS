<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractController
 *
 * @author ANDREW
 */
class App_AbstractController extends Zend_Controller_Action {

    protected $_modelName = '';

    public function indexAction() {
        $searchParams = $this->getRequest()->getParam('search', array());
        $adapter = $this->{$this->_modelName . 'sModel'}->fetchPaginatorAdapter($searchParams);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(10);
        $page = $this->getRequest()->getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
        if ($this->getRequest()->isPost()) {
            $this->view->isPost = true;
        }
        $this->view->{$this->_modelName . 'sCount'} = $adapter->count();
    }

    public function createUpdateAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/nicEdit.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.fileupload.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.fileupload-ui.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.iframe-transport.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.tmpl.min.js');

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/modules/upload/jquery.fileupload-ui.css');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/modules/upload/style.css');
    }

    public function manageAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/nicEdit.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.fileupload.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.fileupload-ui.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.iframe-transport.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.tmpl.min.js');

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/modules/upload/jquery.fileupload-ui.css');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/modules/upload/style.css');

        $searchParams = $this->getRequest()->getParam('search', array());
//        Zend_Debug::fdump($this->_modelName . 'sModel');
        $adapter = $this->{$this->_modelName . 'sModel'}->fetchPaginatorAdapter($searchParams);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(5);
        $page = $this->getRequest()->getParam('page', 1);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
        if ($this->getRequest()->isPost()) {
            $this->view->isPost = true;
        }
        $this->view->{$this->_modelName . 'sCount'} = $adapter->count();
    }

    public function viewAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/fancybox/jquery.mousewheel-3.0.4.pack.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/fancybox/jquery.fancybox-1.3.4.pack.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/fancybox/jquery.fancybox-1.3.4.css');
    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender();
        $id = $this->_request->getParam($this->_modelName . "_id");
        try {
            $this->{$this->_modelName . 'sModel'}->remove($id);
        } catch (Exception $ex) {
            $res = array();
            $res['errorMessage'] = $ex->getMessage();
            echo Zend_Json_Encoder::encode($res);
        }
    }

}

?>

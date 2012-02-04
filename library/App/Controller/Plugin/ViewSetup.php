<?php

require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * Front Controller plug in to set up the view with the Places view helper
 * path and some useful request variables.
 *
 */
class App_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract {

    /**
     * @var Zend_View
     */
    protected $_view;

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->init();

        $view = $viewRenderer->view;
        $this->_view = $view;

        // set up common variables for the view
        $view->originalModule = $request->getModuleName();
        $view->originalController = $request->getControllerName();
        $view->originalAction = $request->getActionName();

        // add helper path to View/Helper directory within this library
        $prefix = 'App_View_Helper';
        $dir = dirname(__FILE__) . '/../../View/Helper';
        $view->addHelperPath($dir, $prefix);

        // setup initial head place holders
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->_controllerCSSLink($request);
        $this->_controllerJSLink($request);
    }

    protected function _controllerCSSLink(Zend_Controller_Request_Http $request) {
        $cssFiles = array();
        $cssFiles[] = 'css/' . $request->getControllerName() . '/' . $request->getActionName() . '.css';
        $cssFiles[] = 'css/site.css';
        $cssFiles[] = 'css/common.css';
        foreach ($cssFiles as $file_uri) {
            if (file_exists($file_uri)) {
                $this->_view->headLink()->appendStylesheet($this->_view->baseUrl() . '/' . $file_uri);
            }
        }
    }

    protected function _controllerJSLink(Zend_Controller_Request_Http $request) {
        $jsFiles = array();
        $jsFiles[] = 'js/' . $request->getControllerName() . '/' . $request->getActionName() . '.js';
        $jsFiles[] = 'js/' . $request->getControllerName() . '/' . $request->getControllerName() . '.js';
        $jsFiles[] = 'js/ajaxRequest.js';
        $jsFiles[] = 'js/toolbar.js';
        $jsFiles[] = 'js/site.js';
        foreach ($jsFiles as $file_uri) {
//            Zend_Debug::fdump($file_uri, '$file_uri');
            if (file_exists($file_uri)) {
//                Zend_Debug::fdump('exist');
                $this->_view->headScript()->appendFile($this->_view->baseUrl() . '/' . $file_uri);
            }
        }
    }

}
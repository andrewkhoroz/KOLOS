<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_config;

    public function __construct($application) {
        Error_Reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'on');
        parent::__construct($application);
    }

    protected function _initConfig() {
        $this->bootstrap('FrontController');
        $front = $this->getResource('frontController');
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'development');
        $this->_config = $config;
    }

    protected function _initCache() {
        $frontendOptions = array(
            'lifetime' => 120, // время жизни кэша - 30 секунд
            'automatic_serialization' => true // уже установлено по умолчанию
        );
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/../var/cache';
        $backendOptions = array('cache_dir' => $dir);
        $cache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
    }

    protected function _initView() {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('KOLOS')
                ->setSeparator(' :: ');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=windows-1251');
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                        'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    public function _initJquery() {
        $view = new Zend_View();
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        $view->jQuery()
                ->enable()
                ->setLocalPath('/js/jQuery/jquery-1.6.1.min.js')
                ->addStylesheet('/css/jQuery/jquery.ui.custom.css')
                ->setUiLocalPath('/js/jQuery/jquery.ui.min.js')
                ->uiEnable();
    }

    protected function _initRequest() {
        // Ensure the front controller is initialized
        $this->bootstrap('FrontController');

        // Retrieve the front controller from the bootstrap registry
        $front = $this->getResource('FrontController');
        $request = new Zend_Controller_Request_Http();
        $front->setRequest($request);

        // Ensure the request is stored in the bootstrap registry
        return $request;
    }

    protected function _initAutoloader() {
        //  That will allow you to load any arbitrary class that is in your path,
        //  even if you've registered specific autoload prefixes in your application.ini
        //Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                    'basePath' => APPLICATION_PATH,
                    'namespace' => '',
                    'resourceTypes' => array(
                        'form' => array(
                            'path' => 'forms/',
                            'namespace' => 'Form_'
                        ),
                        'model' => array(
                            'path' => 'models/',
                            'namespace' => 'Model_'
                        ),
                    ),
                ));
    }

    protected function _initSession() {
        // should probably only do this for modules other than API? -- GAW
        $session = Model_Session::getSession();
        $sessionCustom = new Zend_Session_Namespace('custom');
//        Zend_Debug::fdump($session->storage->user, '$session->storage->user');

        if (isset($sessionCustom->numberOfPageRequests)) {
            $sessionCustom->numberOfPageRequests++;
        } else {
            $sessionCustom->numberOfPageRequests = 1; // начальное значение
        }
        Zend_Registry::set('numberOfPageRequests', $sessionCustom->numberOfPageRequests);
        $session->setExpirationSeconds($this->_config->auth->timeout);
        Zend_Session::setOptions($this->_config->resources->session->toArray());
        return $session;
    }

    //can set another hendler
    protected function _initErrorHandler() {
        $this->bootstrap('FrontController');
        $front = $this->getResource('frontController');
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
                    'module' => 'default',
                    'controller' => 'error',
                    'action' => 'error'
                )));
    }

    /**
     * used for handling top-level navigation
     * @return Zend_Navigation
     */
    protected function _initNavigation() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');

        $container = new Zend_Navigation($config);

        $view->navigation($container);
    }

    protected function _initDefaults() {
        
    }

}

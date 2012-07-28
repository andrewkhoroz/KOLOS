<?php

class IndexController extends Zend_Controller_Action {

    /**
     * The controller's init() function is called before 
     * the action. Usually we use it to set up the ACL
     * restrictions for the actions within the controller.
     *
     */
    public function init() {
//        $uri = $this->_request->getPathInfo();
//        $activeNav = $this->view->navigation()->findByUri($uri);
//        $activeNav->active = true;
//        $activeNav->setClass("active");
    }

    /**
     * This action is the home page of the website
     *
     */
    public function indexAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/jquery.zclip.js');
        $this->view->title = 'Copa del Sol: «Гетеборг» – «Карпати» – 0:1';
        $this->view->headTitle('Welcome');
//        throw new Exception("something bad happened");
//        App_FileLogger::info("hello again!");
    }

    public function testAction() {
        
    }

    /**
     * This action creates the main menu and is called
     * via the action stack
     * 
     * Note that as we are using multiple routes in the
     * bootstrap, we have to specify which route we want
     * to use to generate each url, otherwise the route
     * that was used for this request is used. If you do
     * not specify, then when you are on the about page
     * (which uses the about route), all the urls in the
     * menu will point to /about!
     *
     */
    public function menuAction() {

        $competitinModel = new Model_Competitions();

        $competitions = $competitinModel->fetchCompetitions();

        $mainMenu = array(
            array('title' => 'Змагання', 'url' => $this->view->url(array('controller' => 'competition'), 'default', true),
                'inner_links' => array(
                    array('title' => 'Додати змагання', 'url' => $this->view->url(array('controller' => 'register', 'action' => 'index')))),
                array()
            ),
            array('title' => 'Команди', 'url' => $this->view->url(array('controller' => 'club'), 'default', true), 'inner_links' => array()),
            array('title' => 'Рест', 'url' => $this->view->url(array('controller' => 'index', 'action' => 'rest'), 'default', true), 'inner_links' => array()),
            array('title' => 'Адмін', 'url' => $this->view->url(array('controller' => 'admin', 'action' => 'index'), 'default', true), 'inner_links' => array()),
        );

        $this->view->competitions = $competitions;
        $this->view->menu = $mainMenu;
        $this->_helper->viewRenderer->setResponseSegment('menu');
    }

    /**
     * This action creates the right hand advert and is
     * called via the action stack.
     *
     */
    public function advertAction() {
        $this->view->numberOfPageRequests = Zend_Registry::get('numberOfPageRequests');
        $this->_helper->viewRenderer->setResponseSegment('advert');
    }

//Create under drop down elements of main menu
    private function createInnerMenuCompetition($menuItems, $controller, $action = null) {
        $innerLinks = array();
        $innerLinks[] = array('title' => $item->name, 'url' => $this->view->url(array('controller' => $controller, 'action' => $action, 'id' => $item['id'])));
        foreach ($menuItems as $item) {
            $innerLinks[] = array('title' => $item->name, 'url' => $this->view->url(array('controller' => $controller, 'action' => $action, 'id' => $item['id'])));
        }
        return $innerLinks;
    }

    private function createInnerMenu($menuItems, $controller, $action = null) {
        $innerLinks = array();
        $innerLinks[] = array('title' => $item->name, 'url' => $this->view->url(array('controller' => $controller, 'action' => $action, 'id' => $item['id'])));
        foreach ($menuItems as $item) {
            $innerLinks[] = array('title' => $item->name, 'url' => $this->view->url(array('controller' => $controller, 'action' => $action, 'id' => $item['id'])));
        }
        return $innerLinks;
    }

//Get twitter feeds
    public function restAction() {
        $client = new Zend_Http_Client();
        $query = "Junior%20PHP%20developer";
        $uri = "http://search.twitter.com/search.json?q=$query";
//        $uri="http://kolos/";
        $client->setUri($uri);
        $json = Zend_Json::decode($client->request("GET")->getBody());
//        $response=$client->request("GET")->getBody();

        $this->view->twitterResults = $json['results'];
//        $this->view->output=$response;
    }

    public function sitemapAction() {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo $this->view->navigation()->sitemap();
    }

    }

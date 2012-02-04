<?php

/**
 * Front Controller plug in to set up the action stack.
 *
 */
class App_Controller_Plugin_ActionSetup extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        if (!$request->isXmlHttpRequest()) {

            $actionStack = Zend_Controller_Action_HelperBroker::getStaticHelper('ActionStack');

            $menuAction = clone($request);
            $menuAction->setModuleName('default')
                    ->setActionName('menu')
                    ->setControllerName('index');
            $actionStack->pushStack($menuAction);

//            $advertAction = clone($request);
//            $advertAction->setModuleName('default')
//                    ->setActionName('advert')
//                    ->setControllerName('index');
//            $actionStack->pushStack($advertAction);
        }
    }

}
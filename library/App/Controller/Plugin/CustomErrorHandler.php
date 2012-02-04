<?php

/**
 * Description of App_Controller_Plugin_ErrorHandler
 *
 * @author ANDREW
 */
class App_Controller_Plugin_CustomErrorHandler extends Zend_Controller_Plugin_Abstract {

    //для логування всіх помилок
    public function dispatchLoopShutdown() {
        $respone = $this->getResponse();
        if ($respone->isException()) {
            $exceptions = $respone->getException();

//            $exceptions = Zend_Debug::dump($exceptions, '$exceptions', false);
//            $log = new Zend_Log(new Zend_Log_Writer_Stream($_SERVER['DOCUMENT_ROOT'] . '/logs/applicationExceptions.txt'));
//            $log->debug("\n" .'------------------------' .date('c') . '------------------------' . "\n" . $exceptions);
        }
        $respone->sendHeaders();
        $respone->sendResponse();
    }

}

?>

<?php

class App_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
    function baseUrl()
    {
        $fc = Zend_Controller_Front::getInstance();
        return $this->_baseUrl =  $fc->getBaseUrl();
    }
}

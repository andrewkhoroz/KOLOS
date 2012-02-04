<?php

/**
 * Description of Notice
 *
 * @author KHOROZ
 */
class App_View_Helper_Notice extends Zend_View_Helper_Abstract {

    public $startColor = 16764142;
    public $messages = array();

    public function notice($message="no message") {
        $this->messages[] = $message;

        $this->startColor-=6000;
        $res = "<div style='padding: 4px; text-align: center; background : #" . dechex($this->startColor) . ";'>";

        foreach ($this->messages as $msg) {
            $res.=$msg;
        }

        return $res . "</div>";
        ;
    }

}
?>

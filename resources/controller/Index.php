<?php
/**
 * Description of Index
 *
 * @author gullo
 */
class Controller_Index extends MyFw_Controller {

    function _init() {}

    function indexAction() {

    }







    
    function debugSessionAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();

        Zend_Debug::dump($_SESSION);
        die;
    }

}
?>

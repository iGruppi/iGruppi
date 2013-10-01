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
    

    function errorAction() {
        $errorCode = $this->getParam("code");
        
        switch ($errorCode) {
            case 404:
                $this->view->errorMessage = "Page not found!";
                break;

            default:
                $this->view->errorMessage = "";
                break;
        }
        
        $this->view->errorCode = $errorCode;
    }




    
    function debugSessionAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();

        Zend_Debug::dump($_SESSION);
        die;
    }

}
?>

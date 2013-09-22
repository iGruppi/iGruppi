<?php
/**
 * Description of Dashboard
 *
 * @author gullo
 */
class Controller_Dashboard extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() {
        $gObj = new Model_Groups();
        $this->view->group = $gObj->getGroupById($this->_userSessionVal->idgroup);
    }

}
?>

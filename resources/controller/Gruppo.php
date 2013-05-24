<?php
/**
 * Description of Index
 *
 * @author gullo
 */
class Controller_Gruppo extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() {

    }

    
    function iscrittiAction() {
        
        // get All Iscritti in Group
        $sql = "SELECT u.*, ug.attivo "
              ." FROM users_group AS ug"
              ." LEFT JOIN users AS u ON ug.iduser=u.iduser"
              ." WHERE ug.idgroup= :idgroup"
              ." ORDER BY u.cognome";
        //echo $sql; die;
        $sth = $this->getDB()->prepare($sql);
        $sth->execute(array('idgroup' => $this->_userSessionVal->idgroup));
        
//        Zend_Debug::dump($sth->rowCount()); die;
        $this->view->list = $sth->fetchAll(PDO::FETCH_CLASS);
    }





}
?>

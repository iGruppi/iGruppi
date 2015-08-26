<?php
/**
 * Description of Produttori_Permessi
 *
 * @author gullo
 */
class Plugin_ProduttoriPermessi {

    private $_iduser;
    private $_userSessionVal;


    public function __construct ()
    {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    /**
     * Set permsProduttori in Registry
     * Use Zend_Registry::get("permsProduttori") to call it everywhere in the code
     * 
     * @param MyFw_ControllerFront $cf
     * @return void
     */
    public function preDispatch(MyFw_ControllerFront $cf)
    {
        $uObj = new Model_Db_Users();
        $permsProduttori = new Model_Produttori_Permessi($uObj->getGlobalRefByIduser($this->_iduser), $uObj->getRefByIduserAndIdgroup($this->_iduser, $this->_userSessionVal->idgroup));
        Zend_Registry::set('permsProduttori', $permsProduttori);
    }

    public function  postDispatch(MyFw_ControllerFront $cf)
    {
        //echo "<br>post - Dispatch<br>";
        
    }
}
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

    function indexAction() 
    {
        $gObj = new Model_Db_Groups();
        $this->view->group = $gObj->getGroupById($this->_userSessionVal->idgroup);
        
        // get Ultimi Movimenti Utente
        $cassaObj = new Model_Db_Cassa();
        $movRecs = $cassaObj->getMovimentiByIduser($this->_iduser);
        $movimenti = array();
        $saldo = 0;
        if(count($movRecs) > 0)
        {
            foreach ($movRecs as $movimento) {
                $movObj = new Model_Cassa_Movimento($movimento);
                $movimenti[] = $movObj;
                // calculate SALDO
                $saldo += $movObj->getImporto();
            }
            
        }
        $this->view->movimenti = $movimenti;
        $this->view->saldo = $saldo;
    }

}
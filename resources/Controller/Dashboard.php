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
        // init the SimplePager
        $page = is_null($this->getParam("page")) ? 0 : $this->getParam("page");
        $view = is_null($this->getParam("view")) ? 10 : $this->getParam("view");
        $sPager = new MyFw_SimplePager();
        $sPager->setPage($page);
        $sPager->setView($view);
        $sPager->setURL('/dashboard/index');
        
        // GET DATA for GROUP
        $gObj = new Model_Db_Groups();
        $this->view->group = $gObj->getGroupById($this->_userSessionVal->idgroup);
        
        // get Ultimi Movimenti Utente
        $cassaObj = new Model_Db_Cassa();
        $movRecs = $cassaObj->getMovimentiByIduser($this->_iduser, $sPager->getSQLStartNumber(), $sPager->getSQLLimitNumber());
        $movimenti = array();
        if(count($movRecs) > 0)
        {
            foreach ($movRecs as $movimento) {
                $movObj = new Model_Cassa_Movimento($movimento);
                $movimenti[] = $movObj;
            }
        }
        $this->view->movimenti = $movimenti;
        // set num_result in SimplePager
        $sPager->setNumResults(count($movimenti));
        $this->view->sPager = $sPager;
        
        
        // GET SALDI DI CASSA
        $saldi = $cassaObj->getSaldiIduser($this->_iduser, $this->_userSessionVal->idgroup);
        $this->view->saldi = $saldi;
        
        // GET Ordini in corso
        $listOrd = $cassaObj->getTotaleOrdiniInCorsoByIduser($this->_iduser, $this->_userSessionVal->idgroup);
        // create array of Ordini
        if(count($listOrd) > 0) {
            foreach($listOrd AS &$ordine) 
            {
                $ordine->Model_Ordini_State = Model_Ordini_State_OrderFactory::getOrder($ordine);
            }
        }
        $this->view->ordini_incorso = $listOrd;
    }

}
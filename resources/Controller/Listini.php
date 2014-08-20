<?php
/**
 * Description of Listini
 *
 * @author gullo
 */
class Controller_Listini extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() 
    {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->view->userSessionVal = $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() 
    {
        // get Elenco Listini per Gruppo
        $lObj = new Model_Listini();
        $listiniArray = $lObj->getListiniByIdgroup($this->_userSessionVal->idgroup);
        $listini = array();
        if(!is_null($listiniArray)) {
            foreach ($listiniArray as $stdListino) {
                $mllObj = new Model_Listini_Listino($stdListino);
                // check for Referente Listino
                if( $mllObj->is_Referente() ) {
                    array_unshift($listini, $mllObj);
                } else {
                    array_push($listini, $mllObj);
                }
            }
        }
        
        $this->view->listini = $listini;
        //Zend_Debug::dump($listini);
        
    }
    
    function addAction()
    {
        
    }
    
    function editAction()
    {
        $idlistino = $this->getParam("idlistino");
        // get Elenco Listini per Gruppo
        $lObj = new Model_Listini();
        $listino = $lObj->getListinoById($idlistino);
        Zend_Debug::dump($listino);
                
    }
    
    function prodottiAction()
    {
        
    }
    
    function viewAction()
    {
        
    }
    
}
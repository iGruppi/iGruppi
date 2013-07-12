<?php

/**
 * Description of JxDomains
 * 
 * @author gullo
 */
class Controller_JxDomains extends MyFw_Controller {
    
    private $_userSessionVal;
    
    function _init() {
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }
    
    function getInfoAction() {
        $iddomain = $this->getParam("iddomain");

        // get domain
        $domObj = new Model_Domains();
        $domain = $domObj->getDomainById($iddomain);
        $this->view->domain = $domain;
        
        // get contacts domain
        $contact = $domObj->getContacts($iddomain, $this->_userSessionVal->idapp_selected);
        $this->view->contact = $contact;
        
        if(!is_null($contact)) {
            // get contacts times
            $idad = $contact->idad;
            $times = $domObj->getContactsTimes($idad);
            $this->view->times = $times;
        }
        
        $this->initJson();
    }

    function editContactInfoAction() {

    }
    
    function addContactInfoAction() {

    }
    
    
}
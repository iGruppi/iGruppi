<?php
/**
 * Description of Model_Produttori_Referente
 * 
 * @author gullo
 */
class Model_Produttori_Referente {
    
    private $_iduser_ref;
    
    function __construct($iduser) {
        $this->_iduser_ref = $iduser;
    }
    
    function is_Referente() {
        $auth = Zend_Auth::getInstance();
        $iduser = $auth->getIdentity()->iduser;  
        return ($this->_iduser_ref == $iduser);
    }
    
}
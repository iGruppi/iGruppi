<?php

/**
 * Description of Listino
 * 
 * @author gullo
 */
class Model_Listini_Listino {
    
    private $_listObject = null;
    
    public function __construct(stdClass $l) 
    {
        $this->_listObject = $l;
    }
    
    public function getIdListino()
    {
        return $this->_listObject->idlistino;
    }
    
    public function getDescrizione()
    {
        return $this->_listObject->descrizione;
    }
    
    public function getTipoListino()
    {
        
    }
    
    public function isValido()
    {
        
    }
    
    public function is_Referente()
    {
        $auth = Zend_Auth::getInstance();
        $iduser = $auth->getIdentity()->iduser;  
        return ($iduser == $this->_listObject->iduser_ref);
    }
/*
 *  PERMISSION
 */    
    
    public function canManageListino()
    {
        return $this->is_Referente();
    }
}

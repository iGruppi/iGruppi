<?php

/**
 * This is the Factory to manage the LISTINO
 * 
 * @author gullo
 */
class Model_Listini_Listino extends Model_AF_AbstractCoR
{
    /**
     * set OrdineFactory as factory class
     * @return void
     */
    public function __construct() {
        $this->factoryClass = new Model_AF_ListinoFactory();
    }

            
    
/*  **************************************************************************
 *  PERMISSION
 */    
    
    public function canManageListino()
    {
        return $this->isReferenteProduttore();
    }
    
    public function canManageCondivisione()
    {
        return $this->isOwner();
    }
    
    public function canEditName()
    {
        return $this->isOwner();   
    }
    
    public function canSetValidita()
    {
        return $this->isOwner();   
    }
    
    public function canUpdatePrezzi()
    {
        return $this->isOwner();
    }
    
    public function canSetDataListino()
    {
        return $this->isOwner();   
    }
    
    
/*  **************************************************************************
 *  MISC
 *****************************************************************************/    

    private function isReferenteProduttore()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->refObject->is_Referente($this->getIdProduttore());
    }
    
    private function isOwner()
    {
        return ($this->getMyIdGroup() == $this->getMasterGroup()->getIdGroup());
    }
    
    
}

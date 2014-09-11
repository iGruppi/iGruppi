<?php

/**
 * This is the Factory to manage the LISTINO
 * 
 * @author gullo
 */
class Model_Listini_Listino extends Model_AF_AbstractManipulator 
{

    /**
     * Create Listino by Factory method
     */
    public function __construct() {
        parent::create(new Model_AF_ListinoFactory());
    }
            
    
/*  **************************************************************************
 *  PERMISSION
 */    
    
    private function isReferenteProduttore()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->refObject->is_Referente($this->getDati()->getIdProduttore());
    }
    
    private function isOwner()
    {
        return ($this->getGroups()->getMyIdGroup() == $this->getGroups()->getMasterGroup()->getIdGroup());
    }
    
    public function canManageListino()
    {
        return $this->isReferenteProduttore();
    }
    
    public function canManageCondivisione()
    {
        return $this->isOwner();
    }

    
/*  **************************************************************************
 *  SAVE CHANGES TO DB
 */    
    
    public function save()
    {
        // save Dati
        $res1 = $this->getDati()->saveToDB();
        // save Groups
        $res2 = $this->getGroups()->saveToDB();
        
        return ($res1 && $res2);
    }
    
}

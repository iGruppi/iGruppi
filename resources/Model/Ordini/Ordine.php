<?php
/**
 * This is the Factory to manage the ORDINE
 * 
 * @author gullo
 */
class Model_Ordini_Ordine extends Model_AF_AbstractCoR 
{
    /**
     * set OrdineFactory as factory class
     * @return void
     */
    public function __construct(Model_AF_AbstractFactory $factoryClass) {
        $this->factoryClass = $factoryClass;
    }
    
    /**
     * Append States Pattern to the Chain
     * @return $this Model_AF_AbstractCoR
     */
    public function appendStates(Model_Ordini_State_OrderInterface $sof)
    {
        return $this->append("States", $sof );
    }

    
/*  **************************************************************************
 *  PERMISSION
 */    
    
    /**
     * Return TRUE if this group is the Master Group
     * @return bool
     */
    private function isOwner()
    {
        return ($this->getMyIdGroup() == $this->getMasterGroup()->getIdGroup());
    }
    
    /**
     * Return TRUE if iduser session is Referente ordine
     * @return bool
     */
    private function isReferenteOrdine()
    {
        $iduser = Zend_Auth::getInstance()->getIdentity()->iduser;
        return ($iduser == $this->getMyGroup()->getRefIdUser());
    }
    
    /**
     * Return TRUE if iduser session is Admin and can manage Ordini
     * @return bool
     */
    private function isAdminForOrdine()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->aclUserObject->canManageOrdini();
    }
    
    /**
     * Return TRUE if iduser can manage ORDINE
     * @return bool
     */
    public function canManageOrdine()
    {
        if($this->isAdminForOrdine()) {
            return true;
        } else if($this->getMyGroup()->isSetUserRef())
        {
            return $this->isReferenteOrdine();
        } else {
            return false;
        }
    }
    
    /**
     * Return TRUE if iduser can manage CONDIVISIONE ORDINE
     * @return bool
     */
    public function canManageCondivisione()
    {
        return ($this->isOwner() && $this->isReferenteOrdine());
    }
    
    /**
     * Return TRUE if iduser can manage DATE ORDINE
     * @return bool
     */
    public function canManageDate()
    {
        return ($this->isOwner() && $this->isReferenteOrdine());
    }
    
    /**
     * Return TRUE if can manage UsersRef for this group
     * @return bool
     */
    public function canManageUsersRef()
    {
        return $this->isAdminForOrdine();
    }
}

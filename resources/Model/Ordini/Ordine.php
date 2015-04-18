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

    /**
     * Direct access to Extra Spese fot MyGroup
     * @return Model_Ordini_Extra_Spese
     */
    public function getSpeseExtra()
    {
        return $this->getMyGroup()->getExtra();
    }
    
    
/*  **************************************************************************
 *  PERMISSION
 *  Lista delle funzionalità dell'ordine:

        Avanzamento Stato
        Visibilità (SI/NO)
        Validità ordine (date dal/al)
        Condivisione
        Referente Ordine del proprio Gruppo
        Gestione Spese Extra
        Inserimento Nuovo Prodotto
        Modifica Prodotti (Disponibilità, Prezzo e Offerta)
        Modifica Qtà ordinate

 *  
 *      I RUOLI coinvolti sono:

          * Amministratore del Gruppo
          * Supervisore Ordine
          * Referente Ordine
          * Tesoriere
 * 
 */    
    
    /**
     * Return TRUE if Iduser is SUPERVISORE
     * @return bool
     */
    private function isSupervisoreOrdine()
    {
        $iduser = Zend_Auth::getInstance()->getIdentity()->iduser;
        return ($iduser == $this->getSupervisore_IdUser());
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
    private function isAdminForGroup()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->aclUserObject->isFounder();
    }
    
    
    
    /**
     * Return TRUE if iduser can manage ORDINE
     * @return bool
     */
    public function canManageOrdine()
    {
        if($this->isAdminForGroup()) {
            return true;
        } else if($this->getMyGroup()->isSetUserRef())
        {
            return $this->isReferenteOrdine();
        } else {
            return false;
        }
    }
    
    /**
     * Return TRUE if can UPDATE STATO
     * @return boolean
     */
    public function canUpdateStato()
    {
        return $this->isSupervisoreOrdine();
    }
    
    /**
     * Return TRUE if can update the field ordini_groups.visibile
     * @return boolean
     */
    public function canUpdateVisibile()
    {
        return ($this->isReferenteOrdine() && $this->is_Pianificato());
    }

    /**
     * Return TRUE if iduser can manage DATE ORDINE
     * @return bool
     */
    public function canManageDate()
    {
        return ($this->isSupervisoreOrdine() && ($this->is_Pianificato() || $this->is_Aperto() || $this->is_Chiuso() ));
    }    
    
    /**
     * Return TRUE if iduser can manage CONDIVISIONE ORDINE
     * @return bool
     */
    public function canManageCondivisione()
    {
        return ($this->isSupervisoreOrdine() && $this->is_Pianificato());
    }
    
    /**
     * Return TRUE if can manage UsersRef for this group
     * @return bool
     */
    public function canManageReferente()
    {
        return ($this->isAdminForGroup() && !$this->is_Archiviato());
    }

    
    /**
     * Return TRUE if can manage SPESE EXTRA
     * @return boolean
     */
    public function canManageSpeseExtra()
    {
        return ($this->isReferenteOrdine() && $this->is_Arrivato());
    }
    
    /**
     * Return TRUE if can manage SPESE EXTRA
     * @return boolean
     */
    public function canAddNewProdotti()
    {
        return false; // TODO
    }
    
    /**
     * Return TRUE Se il Referente può modificare i prodotti (prezzo, offerta e disponibilità)
     * @return boolean
     */
    public function canModificaProdotti()
    {
        return ($this->isSupervisoreOrdine() && ($this->is_Pianificato() || $this->is_Arrivato()) );
    }
        
    /**
     * Return TRUE if can manage SPESE EXTRA
     * @return boolean
     */
    public function canModificaQtaOrdinate()
    {
        return ($this->isReferenteOrdine() && ($this->is_Chiuso() || $this->is_Arrivato()) );
    }
    
    /**
     * Return TRUE if can send ORDINE by Email directly to Produttore
     */
    public function canInviaOrdineByEmail()
    {
        return false; // TODO!
    }
    
    /**
     * Return TRUE if it is CONTABILE and can close that ORDER
     * @return bool
     */
    public function canArchiviaOrdine()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return ($userSessionVal->aclUserObject->isContabile() && $this->is_Consegnato());
    }
    
}

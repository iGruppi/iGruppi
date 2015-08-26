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
    
    public function isMultiProduttore()
    {
        return count($this->getProduttoriList()) > 1;
    }

    /**
     * Return Extra Spese part of Group
     * For PUB get data from Master, others from MyGROUP
     * @return Model_Ordini_Extra_Spese
     */
    public function getSpeseExtra()
    {
        if($this->isPubblico()) {
            return $this->getMasterGroup()->getExtra();
        } else {
            return $this->getMyGroup()->getExtra();
        }
    }
    
    /**
     * Return VALIDITA part of Group
     * For PUB get data from Master, others from MyGROUP
     * @return Model_Builder_Parts_Validita
     */
    public function getValidita()
    {
        if($this->isPubblico()) {
            return $this->getMasterGroup()->getValidita();
        } else {
            return $this->getMyGroup()->getValidita();
        }
    }
    
    /**
     * Return VISIBILE part of Group
     * For PUB get data from Master, others from MyGROUP
     * @return Model_Builder_Parts_FlagSN
     */
    public function getVisibile()
    {
        if($this->isPubblico()) {
            return $this->getMasterGroup()->getVisibile();
        } else {
            return $this->getMyGroup()->getVisibile();
        }
    }
    
    
    
/*  **************************************************************************
 *  PERMISSION
 *  Lista delle funzionalità dell'ordine:

        Avanzamento Stato
        Visibilità (SI/NO)
        Validità ordine (date dal/al)
        Condivisione
        Incaricato Ordine del proprio Gruppo
        Gestione Spese Extra
        Inserimento Nuovo Prodotto
        Modifica Prodotti (Prezzo e Offerta)
        Modifica Prodotti (Disponibilità)
        Modifica Qtà ordinate

 *  
 *      I RUOLI coinvolti sono:

          * Amministratore del Gruppo
          * Supervisore Ordine
          * Incaricato Ordine
          * Tesoriere
 * 
 */    
    
    /**
     * Return TRUE if Iduser is SUPERVISORE
     * @return bool
     */
    public function isSupervisoreOrdine()
    {
        $iduser = Zend_Auth::getInstance()->getIdentity()->iduser;
        return ($iduser == $this->getSupervisore_IdUser());
    }
    
    /**
     * Return TRUE if iduser session is Incaricato ordine
     * @return bool
     */
    public function isIncaricatoOrdine()
    {
        $iduser = Zend_Auth::getInstance()->getIdentity()->iduser;
        return ($iduser == $this->getMyGroup()->getIdUser_Incaricato());
    }
    
    /**
     * Return TRUE if iduser session is Admin and can manage Ordini
     * @return bool
     */
    public function isAdminForGroup()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->aclUserObject->isFounder();
    }
    
    /**
     * Return TRUE if idgroup is the MASTER group
     * @return boolean
     */
    public function isOwnerGroup()
    {
        return ($this->getMyIdGroup() == $this->getMasterGroup()->getIdGroup());
    }
    
    
    
    /**
     * Return TRUE if iduser can manage ORDINE
     * @return bool
     */
    public function canManageOrdine()
    {
        if($this->isAdminForGroup() || $this->isSupervisoreOrdine()) {
            return true;
        } else if($this->getMyGroup()->isSetUserRef())
        {
            return $this->isIncaricatoOrdine();
        } else {
            return false;
        }
    }
    
    /**
     * Return TRUE if can modify Descrizione
     * @return boolean
     */
    public function canManageDescrizione()
    {
        return $this->isSupervisoreOrdine();
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
        return ($this->isIncaricatoOrdine() && $this->is_Pianificato()) OR
               ($this->isAdminForGroup() && $this->is_Aperto());
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
    public function canManageIncaricato()
    {
        return ($this->isAdminForGroup() && !$this->is_Archiviato());
    }

    
    /**
     * Return TRUE if can manage SPESE EXTRA
     * @return boolean
     */
    public function canManageSpeseExtra()
    {
        return ($this->isIncaricatoOrdine() && $this->is_Arrivato());
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
     * Return TRUE se può modificare i prodotti (prezzo e offerta)
     * @return boolean
     */
    public function canModificaProdottiPrezzo()
    {
        return ($this->isSupervisoreOrdine() && ($this->is_Pianificato() || $this->is_Arrivato()) );
    }
    
    /**
     * Return TRUE se può modificare i prodotti (disponibilità)
     * @return boolean
     */
    public function canModificaProdottiDisponibilita()
    {
        return (
                ( $this->isSupervisoreOrdine() && ($this->is_Pianificato() || $this->is_Aperto()) ) OR
                ( $this->isIncaricatoOrdine() && $this->is_Arrivato() )
        );
    }
        
    /**
     * Return TRUE if can manage SPESE EXTRA
     * @return boolean
     */
    public function canModificaQtaOrdinate()
    {
        return ($this->isIncaricatoOrdine() && ($this->is_Chiuso() || $this->is_Arrivato()) );
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

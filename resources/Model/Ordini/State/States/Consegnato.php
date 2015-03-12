<?php
/**
 * Class to manage State CONSEGNATO
 */
class Model_Ordini_State_States_Consegnato extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Consegnato";
    protected $_className = "Consegnato";
    

    /**
     * Returns the Next state: ARCHIVIATO
     * @return string
     */
    public function getNextState()
    {
        return Model_Ordini_State_States_Archiviato::STATUS_NAME;
    }
    
    /**
     * Returns the Next state: ARRIVATO
     * @return string
     */
    public function getPrevState()
    {
        return Model_Ordini_State_States_Arrivato::STATUS_NAME;
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Consegnato() 
    { 
        return true; 
    }
    
    /**
     * Permission states for Referente
     * @return bool
     */
    public function canRef_ModificaProdotti() 
    { 
        return true; 
    }
    // Se il Referente può modificare le Quantità ordinate dagli utenti
    public function canRef_ModificaQtaOrdinate() 
    {
        return true;
    }

    public function canUser_ViewDettaglio() 
    {
        return true;
    }
    
    
    public function canContabile_ArchiviaOrdine() 
    {
        return true;
    }

}

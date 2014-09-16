<?php
/**
 * Class to manage State APERTO
 */
class Model_Ordini_State_States_Aperto extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Aperto";
    protected $_className = "Aperto";
    

    /**
     * Returns the Next state: CHIUSO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getNextState()
    {
        return new Model_Ordini_State_States_Chiuso( $this->_ordine );
    }
    
    /**
     * Returns the Next state: PIANIFICATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState()
    {
        return new Model_Ordini_State_States_Pianificato( $this->_ordine );
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Aperto() 
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
    public function canUser_OrderProducts() 
    {
        return true;
    }
    public function canUser_ViewDettaglio() 
    {
        return true;
    }
    
    
}

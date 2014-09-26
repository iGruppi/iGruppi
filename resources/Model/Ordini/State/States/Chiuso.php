<?php
/**
 * Class to manage State CHIUSO
 */
class Model_Ordini_State_States_Chiuso extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Chiuso";
    protected $_className = "Chiuso";
    

    /**
     * Returns the Next state: INVIATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getNextState()
    {
        return new Model_Ordini_State_States_Inviato( $this->_ordine );
    }
    
    /**
     * Returns the Next state: APERTO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState()
    {
        return new Model_Ordini_State_States_Aperto( $this->_ordine );
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Chiuso() 
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
    public function canRef_InviaOrdine() 
    {
        return true;
    }
    public function canUser_ViewDettaglio() 
    {
        return true;
    }
    
    
}

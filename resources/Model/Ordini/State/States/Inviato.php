<?php
/**
 * Class to manage State INVIATO
 */
class Model_Ordini_State_States_Inviato extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Inviato";
    protected $_className = "Inviato";
    

    /**
     * Returns the Next state: ARRIVATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getNextState()
    {
        return new Model_Ordini_State_States_Arrivato( $this->_ordine );
    }
    
    /**
     * Returns the Next state: CHIUSO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState()
    {
        return new Model_Ordini_State_States_Chiuso( $this->_ordine );
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Inviato() 
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
    public function canUser_ViewDettaglio() 
    {
        return true;
    }
    
}

<?php
/**
 * Class to manage State ARRIVATO
 */
class Model_Ordini_State_States_Arrivato extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Arrivato";
    protected $_className = "Arrivato";
    

    /**
     * Returns the Next state: CONSEGNATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getNextState()
    {
        return new Model_Ordini_State_States_Consegnato( $this->_ordine );
    }
    
    /**
     * Returns the Next state: INVIATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState()
    {
        return new Model_Ordini_State_States_Inviato( $this->_ordine );
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Arrivato() 
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

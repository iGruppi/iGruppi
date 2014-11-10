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
     * @return string
     */
    public function getNextState()
    {
        return Model_Ordini_State_States_Consegnato::STATUS_NAME;
    }
    
    /**
     * Returns the Next state: INVIATO
     * @return string
     */
    public function getPrevState()
    {
        return Model_Ordini_State_States_Inviato::STATUS_NAME;
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

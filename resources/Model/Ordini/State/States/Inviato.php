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
     * @return string
     */
    public function getNextState()
    {
        return Model_Ordini_State_States_Arrivato::STATUS_NAME;
    }
    
    /**
     * Returns the Next state: CHIUSO
     * @return string
     */
    public function getPrevState()
    {
        return Model_Ordini_State_States_Chiuso::STATUS_NAME;
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

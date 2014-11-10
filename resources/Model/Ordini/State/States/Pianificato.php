<?php
/**
 * Class to manage State PIANIFICATO
 */
class Model_Ordini_State_States_Pianificato extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Pianificato";
    protected $_className = "Pianificato";
    

    /**
     * Returns the Next state: APERTO
     * @return string
     */
    public function getNextState()
    {
        return Model_Ordini_State_States_Aperto::STATUS_NAME;
    }
    
    /**
     * Returns the Previous state: NULL (it does not exists, this is the FIRST state!)
     * @return null
     */
    public function getPrevState()
    {
        return null;
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Pianificato() 
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
    
    
}

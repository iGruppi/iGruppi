<?php
/**
 * Class to manage State ARCHIVIATO
 */
class Model_Ordini_State_States_Archiviato extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Archiviato";
    const STATUS_FIELD_DATA = null;    
    protected $_className = "Archiviato";
    

    /**
     * Returns the Next state: NULL (it does NOT exists, this is the LAST state!)
     * @return null
     */
    public function getNextState()
    {
        return null;
    }
    
    /**
     * Returns the Next state: CONSEGNATO
     * @return string
     */
    public function getPrevState()
    {
        return Model_Ordini_State_States_Consegnato::STATUS_NAME;
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Archiviato() 
    { 
        return true; 
    }
    public function canUser_ViewDettaglio() 
    {
        return true;
    }
    
    
}

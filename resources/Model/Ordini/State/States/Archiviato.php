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
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState()
    {
        return new Model_Ordini_State_States_Consegnato( $this->_ordine );
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

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
    const STATUS_FIELD_DATA = "data_fine";
    protected $_className = "Chiuso";
    

    /**
     * Returns the Next state: INVIATO
     * @return string
     */
    public function getNextState()
    {
        return Model_Ordini_State_States_Inviato::STATUS_NAME;
    }
    
    /**
     * Returns the Next state: APERTO
     * @return string
     */
    public function getPrevState()
    {
        return Model_Ordini_State_States_Aperto::STATUS_NAME;
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Chiuso() 
    { 
        return true; 
    }
    
    public function canUser_ViewDettaglio() 
    {
        return true;
    }
    
    
}

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
    const STATUS_FIELD_DATA = "data_inizio";
    protected $_className = "Aperto";
    

    /**
     * Returns the Next state: CHIUSO
     * @return string
     */
    public function getNextState()
    {
        return Model_Ordini_State_States_Chiuso::STATUS_NAME;
    }
    
    /**
     * Returns the Next state: PIANIFICATO
     * @return string
     */
    public function getPrevState()
    {
        return Model_Ordini_State_States_Pianificato::STATUS_NAME;
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Aperto() 
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

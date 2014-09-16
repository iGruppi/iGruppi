<?php
/**
 * Class to manage State CONSEGNATO
 */
class Model_Ordini_State_States_Consegnato extends Model_Ordini_State_OrderAbstract
{
    /**
     * Status name
     */
    const STATUS_NAME = "Consegnato";
    protected $_className = "Consegnato";
    

    /**
     * Returns the Next state: ARCHIVIATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getNextState()
    {
        return new Model_Ordini_State_States_Archiviato( $this->_ordine );
    }
    
    /**
     * Returns the Next state: ARRIVATO
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState()
    {
        return new Model_Ordini_State_States_Arrivato( $this->_ordine );
    }
    
    /**
     *  Return true for this state
     * @return bool
     */
    public function is_Consegnato() 
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

    /**
     * Permission states for Referente
     * @return bool
     */
    public function canRef_ModificaQtaOrdinate() 
    {
        return true;
    }
    public function canUser_ViewDettaglio() 
    {
        return true;
    }

}

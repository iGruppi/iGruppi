<?php
/**
 * Abstract class OrderAbstract
 */
abstract class Model_Ordini_State_OrderAbstract implements Model_Ordini_State_OrderInterface
{
    
    protected $_statiWithDate = array(
        1 => 'Aperto',
        2 => 'Chiuso',
        3 => 'Inviato',
        4 => 'Arrivato',
        5 => 'Consegnato',
    );
    
    
    /**
     * @var array
     */
    protected $_ordine;
    
    /**
     * @param stdclass $ordine
     * @throws \Exception
     */
    public function __construct(stdclass $ordine)
    {
        if (empty($ordine)) {
            throw new Exception('Order can not be empty!');
        }
        $this->_ordine = $ordine;
    }    
    
    /**
     * @return string The state name
     */
    public function getStateName()
    { 
        return $this::STATUS_NAME;
    }
    
    /**
     * Return the Array of ALL Previouses Stati
     * @return array
     */
    public function getArrayPrevStati()
    {
        $ar = array();
        $index = array_search($this::STATUS_NAME, $this->_statiWithDate);
        while($index) {
            // get STATUS_FIELD_DATA constant for any status
            $cName = "Model_Ordini_State_States_" . $this->_statiWithDate[$index];
            $sfd = $cName::STATUS_FIELD_DATA;
            $ar[$this->_statiWithDate[$index]] = $this->_ordine->$sfd;
            $index = $index - 1;
        }
        return array_reverse($ar);
    }


    /**
     * Returns the Next state object
     * @return (null|Model_Ordini_State_OrderAbstract)
     */
    public function getNextState()
    {
        return null;
    }

    /**
     * Returns the Previous state object
     * @return (null|Model_Ordini_State_OrderAbstract)
     */
    public function getPrevState()
    {
        return null;
    }
    
    /**
     * @return bool Bool value of moving action to the NEXT STATE
     */
    public function moveToNextState()
    {
        $mover = new Model_Ordini_State_Mover($this->_ordine);
        return $mover->moveToStatus($this->getNextState());
    }
    
    /**
     * @return bool Bool value of moving action to the PREV STATE
     */
    public function moveToPrevState()
    {
        $mover = new Model_Ordini_State_Mover($this->_ordine);
        return $mover->moveToStatus($this->getPrevState());
    }
    
    /**
     *  Verify states
     * @return bool
     */
    public function is_Pianificato() { return false; }
    public function is_Aperto() { return false; }
    public function is_Chiuso() { return false; }
    public function is_Inviato() { return false; }
    public function is_Arrivato() { return false; }
    public function is_Consegnato() { return false; }
    public function is_Archiviato() { return false; }
    
    /**
     * @return string Return the CSS Class for a state
     */
    public function getStatusCSSClass() 
    { 
        return $this->_className; 
    }            
    
    /**
     * Permission states for User
     * @return bool
     */
    // Se l'utente può ordinare prodotti
    public function canUser_OrderProducts() { return false; }
    // Se l'utente può visualizzare il Dettaglio di un ordine
    public function canUser_ViewDettaglio() { return false; }
    
    
}

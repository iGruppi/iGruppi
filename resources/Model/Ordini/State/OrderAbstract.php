<?php
/**
 * Abstract class OrderAbstract
 */
abstract class Model_Ordini_State_OrderAbstract implements Model_Ordini_State_OrderInterface
{
    
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
            throw new \Exception('Order can not be empty!');
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
     * Permission states for Referente
     * @return bool
     */
    // Se il Referente può modificare i prodotti (prezzi e disponibilità)    
    public function canRef_ModificaProdotti() { return false; }
    // Se il Referente può modificare le Quantità ordinate dagli utenti
    public function canRef_ModificaQtaOrdinate() { return false; }
    // Se il referente può inviare l'ordine
    public function canRef_InviaOrdine() { return false; }
    
    
    /**
     * Permission states for User
     * @return bool
     */
    // Se l'utente può ordinare prodotti
    public function canUser_OrderProducts() { return false; }
    // Se l'utente può visualizzare il Dettaglio di un ordine
    public function canUser_ViewDettaglio() { return false; }
    
    
}

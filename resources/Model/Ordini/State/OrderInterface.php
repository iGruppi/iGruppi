<?php
/**
 * Interface OrderInterface
 */
interface Model_Ordini_State_OrderInterface
{

    /**
     * @return string The state name
     */
    public function getStateName();

    /**
     * Returns the Next state object
     * @return string
     */
    public function getNextState();
    
    /**
     * Returns the Next state object
     * @return string
     */
    public function getPrevState();
    
    /**
     * @return bool Bool value of moving action to the NEXT STATE
     */
    public function moveToNextState();
    
    /**
     * @return bool Bool value of moving action to the PREV STATE
     */    
    public function moveToPrevState();
    
    /**
     *  Verify states
     * @return bool
     */
    public function is_Pianificato();
    public function is_Aperto();
    public function is_Chiuso();
    public function is_Inviato();
    public function is_Arrivato();
    public function is_Consegnato();
    public function is_Archiviato();
    
    /**
     * @return string Return the CSS Class for a state
     */
    public function getStatusCSSClass();
    
    /**
     * Permission states for User
     * @return bool
     */
    // Se l'utente può ordinare prodotti
    public function canUser_OrderProducts();
    // Se l'utente può visualizzare il Dettaglio di un ordine
    public function canUser_ViewDettaglio();
    
}

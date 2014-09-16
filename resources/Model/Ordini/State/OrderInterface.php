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
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getNextState();
    
    /**
     * Returns the Next state object
     * @return Model_Ordini_State_OrderAbstract
     */
    public function getPrevState();

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
     * Permission states for Referente
     * @return bool
     */
    // Se il Referente può modificare i prodotti (prezzi e disponibilità)    
    public function canRef_ModificaProdotti();
    // Se il Referente può modificare le Quantità ordinate dagli utenti
    public function canRef_ModificaQtaOrdinate();
    // Se il referente può inviare l'ordine
    public function canRef_InviaOrdine();
    
    
    /**
     * Permission states for User
     * @return bool
     */
    // Se l'utente può ordinare prodotti
    public function canUser_OrderProducts();
    // Se l'utente può visualizzare il Dettaglio di un ordine
    public function canUser_ViewDettaglio();
    
    
}

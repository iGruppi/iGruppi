<?php

/**
 * Description of Model_Ordini_Prodotto
 * Classe per la gestione del singolo Prodotto in un Ordine (tabella prodotti + ordini_prodotti)
 * 
 * @author gullo
 */
class Model_Ordini_Prodotto
    extends Model_Prodotti_Prodotto 
{
    
    // Campi Prodotto (Ordine)
    protected $_arValAvailable_O = array(
        'costo_op',
        'sconto',
        'offerta',
        'qta',
        'qta_reale',
        'disponibile'
    );
    
    function __construct($a) {
        // Aggiungo campi da ordini_prodotti all'array generale
        $this->_arValAvailable = array_merge($this->_arValAvailable, $this->_arValAvailable_O);
        parent::__construct($a);
    }
    
    
/************************************************************
 * GET PREZZO ORDINE (it could be not the same of Prodotti)
 */
    
    // OVERWRITE getPrezzo of Model_Prodotti_Prodotto
    function getPrezzo() {
        return $this->costo_op;
    }

    
/************************************************************
 * GET QTA REALE (it could be different than QTA Ordinata)
 */

    function getQtaReale()
    {
        return ((float)$this->qta_reale > 0) ? (float)$this->qta_reale : 0;
    }
    
    function setQtaReale($qta) {
        $this->qta_reale = (float)$qta;
    }
    
    function addQtaReale($qta) {
        if($this->getQtaReale() > 0) {
            $this->qta_reale += (float)$qta;
        } else {
            $this->setQtaReale($qta);
        }
    }
    
/************************************************************
 * Products ORDINATI methods
 */

    function getQtaOrdinata() {
        return ((int)$this->qta > 0) ? (int)$this->qta : 0;
    }
    
    function setQtaOrdinata($qta) {
        $this->qta = (int)$qta;
    }
    
    function addQtaOrdinata($qta) {
        if($this->getQtaOrdinata() > 0) {
            $this->qta += (int)$qta;
        } else {
            $this->setQtaOrdinata($qta);
        }
    }
    
/************************************************************
 * Products ORDINATI methods
 */

    function getTotale() {
        return $this->getPrezzo() * $this->getQtaReale();
    }
    
    function getTotaleSenzaIva() {
        return $this->getPrezzoSenzaIva() * $this->getQtaReale();
    }

    function isDisponibile() {
        return ($this->disponibile == "S") ? true : false;
    }
}
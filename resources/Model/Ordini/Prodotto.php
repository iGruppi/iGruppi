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
 * Products methods
 */
    
    // OVERWRITE getPrezzo of Model_Prodotti_Prodotto
    function getPrezzo() {
        return $this->costo_op;
    }

    
/************************************************************
 * Products ORDINATI methods
 */

    function setQta($qta) {
        $this->qta = (int)$qta;
    }
    
    function addQta($qta) {
        if($this->getQta() > 0) {
            $this->qta += (int)$qta;
        } else {
            $this->setQta($qta);
        }
    }
    
    function getQta() {
        return ((int)$this->qta > 0) ? (int)$this->qta : 0;
    }
    
    function getTotale() {
        return $this->getPrezzo() * $this->getQta();
    }
    
    function getTotaleReale() {
        return $this->getPrezzo() * $this->getQtaReale();
    }
    
    function getTotaleSenzaIva() {
        return $this->getPrezzoSenzaIva() * $this->getQta();
    }

    function isDisponibile() {
        return ($this->disponibile == "S") ? true : false;
    }
    
    function getQtaReale()
    {
        return ((float)$this->qta_reale > 0) ? (float)$this->qta_reale : 0;
    }
    
}
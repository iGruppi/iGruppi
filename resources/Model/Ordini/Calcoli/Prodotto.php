<?php

/**
 * Description of Model_Ordini_Calcoli_Prodotto
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Prodotto {
    
    private $_arValAvailable = array(
        'idprodotto',
        'idproduttore',
        'idsubcat',
        'codice',
        'descrizione',
        'udm',
        'attivo',
        'costo',
        'aliquota_iva',
        'note',
        'costo_op',
        'sconto',
        'offerta',
        'categoria',
        'qta'
    );
    
    // array values
    private $_arVal;
    
    function __construct($a) {
        $this->_arVal = new stdClass();
        foreach ($this->_arValAvailable AS $f) {
            if(isset($a->$f)) {
                $this->_arVal->$f = $a->$f;
            }
        }
    }
    
    function setQta($qta) {
        $this->_arVal->qta = $qta;
    }
    
    function getQta() {
        return $this->_arVal->qta;
    }
    
    function addQta($qta) {
        if($this->_arVal->qta > 0) {
            $this->_arVal->qta += $qta;
        } else {
            $this->setQta($qta);
        }
    }
    
    function getPrezzoSenzaIva() {
        if($this->getAliquotaIva() > 0) {
            $cc =  ($this->getPrezzo() / ($this->getAliquotaIva() / 100 + 1));
            return round( $cc, 2, PHP_ROUND_HALF_UP);
        } else {
            return $this->getPrezzo();
        }
    }
    
    function getPrezzo() {
        return $this->_arVal->costo_op;
    }
    
    function getAliquotaIva() {
        if(isset($this->_arVal->aliquota_iva)) {
            return $this->_arVal->aliquota_iva;
        }
        return 0;
    }
    
    function getTotale() {
        return $this->getPrezzo() * $this->getQta();
    }
    
    function getTotaleSenzaIva() {
        return $this->getPrezzoSenzaIva() * $this->getQta();
    }
    
    
    function getCodice() {
        return $this->_arVal->codice;
    }
    function getUdm() {
        return $this->_arVal->udm;
    }
    function getDescrizione() {
        return $this->_arVal->descrizione;
    }
}
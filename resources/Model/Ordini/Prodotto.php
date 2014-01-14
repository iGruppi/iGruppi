<?php

/**
 * Description of Model_Ordini_Prodotto
 * 
 * @author gullo
 */
class Model_Ordini_Prodotto {
    
    protected $_arValAvailable = array(
    // Prodotti
        'idprodotto',
        'idproduttore',
        'idcat',
        'idsubcat',
        'codice',
        'descrizione',
        'udm',
        'attivo',
        'costo',
        'aliquota_iva',
        'note',
        'categoria',
        'categoria_sub',
        
    // Prodotti ORDINATI
        'costo_op',
        'sconto',
        'offerta',
        'qta',
        'disponibile'
    );
    
    // array values
    protected $_arVal;
    
    function __construct($a) {
        $this->_arVal = new stdClass();
        foreach ($this->_arValAvailable AS $f) {
            $this->_arVal->$f = (isset($a->$f)) ? $a->$f : ""; // DO NOT USE "null", it creates error on magic __get method
        }
    }
    
    
/************************************************************
 * Products methods
 */
    
    function getPrezzoSenzaIva() {
        if($this->getAliquotaIva() > 0) {
            $cc =  ($this->getPrezzo() / ($this->getAliquotaIva() / 100 + 1));
            return round( $cc, 2, PHP_ROUND_HALF_UP);
        } else {
            return $this->getPrezzo();
        }
    }
    
    function getPrezzoListino() {
        return $this->costo;
    }
    
    function getAliquotaIva() {
        if(!is_null($this->aliquota_iva) && $this->aliquota_iva > 0) 
        {
            return $this->aliquota_iva;
        }
        return 0;
    }

    function isAttivo() {
        return ($this->attivo == "S") ? true : false;
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
    
    function getPrezzo() {
        return $this->costo_op;
    }
    
    function getTotale() {
        return $this->getPrezzo() * $this->getQta();
    }
    
    function getTotaleSenzaIva() {
        return $this->getPrezzoSenzaIva() * $this->getQta();
    }

    function isDisponibile() {
        return ($this->disponibile == "S") ? true : false;
    }

    
    
/************************************************************
 * GET FIELDS values
 */
    
    /*
	* Overloading
	* __get
	*/
    public function __get($property)
    {
        if( isset($this->_arVal->$property)) {
            return $this->_arVal->$property;
        } else {
            throw new Exception("Impossibile leggere la proprieta: $property");
        }
    }
    
}
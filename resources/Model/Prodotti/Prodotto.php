<?php

/**
 * Description of Model_Prodotti_Prodotto
 * Classe per la gestione del singolo Prodotto (tabella Prodotti)
 * 
 * @author gullo
 */
class Model_Prodotti_Prodotto {

    // Campi Prodotto (Listino)
    protected $_arValAvailable = array(
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
    
    function getPrezzoSenzaIva() 
    {
        if($this->getAliquotaIva() > 0) {
            $cc =  ($this->getPrezzo() / ($this->getAliquotaIva() / 100 + 1));
            return round( $cc, 2, PHP_ROUND_HALF_UP);
        } else {
            return $this->getPrezzo();
        }
    }
    
    function getPrezzoListino() 
    {
        return $this->costo;
    }
    
    function getPrezzo() 
    {
        return $this->getPrezzoListino();
    }
        
    
    function getAliquotaIva() 
    {
        if($this->hasAliquotaIva()) 
        {
            return $this->aliquota_iva;
        }
        return 0;
    }
    
    function hasAliquotaIva() 
    {
        return (!is_null($this->aliquota_iva) && $this->aliquota_iva > 0);
    }

    function isAttivo() 
    {
        return ($this->attivo == "S") ? true : false;
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
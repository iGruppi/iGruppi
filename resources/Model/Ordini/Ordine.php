<?php
/**
 * Description of Calcoli
 * 
 * @author gullo
 */
class Model_Ordini_Ordine {
    
    protected $_idordine;
    protected $_ordObj;
    protected $_arProd;
    
    function __construct($idordine, $ordObj) {
        $this->_idordine = $idordine;
        $this->_ordObj = $ordObj;
        $this->setArray();
    }
    
    function getCostoSpedizione() {
        return $this->_ordObj->costo_spedizione;
    }
    
    function hasCostoSpedizione() {
        return ($this->getCostoSpedizione() > 0);
    }
    
    function getElenco() {
        return $this->_arProd;
    }
    
    function getNum() {
        return count($this->_arProd);
    }    
        
}
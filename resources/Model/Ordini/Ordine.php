<?php
/**
 * Description of Calcoli
 * 
 * @author gullo
 */
class Model_Ordini_Ordine {
    
    protected $_idordine;
    protected $_ordObj;
    protected $_arProdOriginal = array();
    protected $_arProd = array();
    
    function setOrdObj($o) {
        $this->_ordObj = $o;
        $this->_idordine = $o->idordine;
    }
    
    function getOrdObj() {
        return $this->_ordObj;
    }
    
    function setProdotti($listProd) {
        if(count($listProd) > 0) {
            // set Products array original
            $this->_arProdOriginal = $listProd;
            // Create instance Model_Ordini_Prodotto for any Product
            foreach ($listProd as $value) {
                if(!isset($this->_arProd[$value->idprodotto])) {
                    $prObj = new Model_Ordini_Prodotto($value);
                    $prObj->setQtaReale(0);
                    $prObj->setQtaOrdinata(0);
                    $this->_arProd[$value->idprodotto] = $prObj;
                }
            }
        }
    }
    
    function getIdOrdine() {
        return $this->_idordine;
    }
    
    function getProdotti() {
        return $this->_arProd;
    }
    
    function getProdotto($idprodotto) {
        return $this->_arProd[$idprodotto];
    }
    
    function getCostoSpedizione() {
        return $this->_ordObj->costo_spedizione;
    }
    
    function hasCostoSpedizione() {
        return ($this->getCostoSpedizione() > 0);
    }
    
    function getIdgroup() {
        return $this->_ordObj->idgroup;
    }
    
}
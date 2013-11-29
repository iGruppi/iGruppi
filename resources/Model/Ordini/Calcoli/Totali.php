<?php

/**
 * Description of Totali
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Totali 
    extends Model_Ordini_Ordine {
    
    
    function getTotale() {
        $t = 0;
        if(count($this->_arProd) > 0) {
            foreach ($this->_arProd as $idprodotto => $objProd) {
                $t += $objProd->getTotale();
            }
        }
        return ($this->hasCostoSpedizione()) ? ($t + $this->getCostoSpedizione()) : $t;
    }
    
    function getTotaleSenzaIva() {
        $t = 0;
        if(count($this->_arProd) > 0) {
            foreach ($this->_arProd as $idprodotto => $objProd) {
                $t += $objProd->getTotaleSenzaIva();
            }
        }
        return $t;
    }
    
    function getTotaleColli() {
        $c = 0;
        if(count($this->_arProd) > 0) {
            foreach ($this->_arProd as $idprodotto => $objProd) {
                $c += $objProd->getQta();
            }
        }
        return $c;
    }
    
    
    
    
    protected function setArray() {
        $ordObj = new Model_Ordini();
        $listOrd = $ordObj->getTotaleProdottiOrdinati($this->_idordine);
        if(count($listOrd) > 0) {
            // Create instance Model_Ordini_Calcoli_Prodotto for any Product
            foreach ($listOrd as $value) {
                $this->_arProd[$value->idprodotto] = new Model_Ordini_Calcoli_Prodotto($value);
            }
        }
    }
    
    
}
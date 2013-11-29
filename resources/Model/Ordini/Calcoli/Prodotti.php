<?php

/**
 * Description of Utenti
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Prodotti 
    extends Model_Ordini_Ordine {
    
    function getTotaleByIdprodotto($idprodotto) {
        $t = 0;
        if(isset($this->_arProd[$iduser]["prodotti"]) && count($this->_arProd[$iduser]["prodotti"]) > 0) {
            foreach ($this->_arProd[$iduser]["prodotti"] as $idprodotto => $objProd) {
                $t += $objProd->getTotale();
            }
        }
        return ($this->hasCostoSpedizione()) ? ($t + $this->getCostoSpedizioneRipartito()) : $t;
    }
    
    
    protected function setArray() {
        $ordObj = new Model_Ordini();
        $listOrd = $ordObj->getParzialiProdottiOrdinatiProdotti($this->_idordine);
        if(count($listOrd) > 0) {
            // Create instance Model_Ordini_Calcoli_Prodotto for any Product
            foreach ($listOrd as $value) {
                $idprodotto = $value->idprodotto;
                if(!isset($this->_arProd[$idprodotto])) {
                    $this->_arProd[$idprodotto]["prodotto"] = new Model_Ordini_Calcoli_Prodotto($value);
                } else {
                    // update Totale Prodotto
                    $this->_arProd[$idprodotto]["prodotto"]->addQta($value->qta);
                }
                $this->_arProd[$idprodotto]["utenti"][$value->iduser] = array( 
                    'nome'    => $value->nome, 
                    'cognome' => $value->cognome,
                    'qta'     => $value->qta,
                );
            }
        }
        // Zend_Debug::dump($this->_arProd);die;
    }    
    
    
}
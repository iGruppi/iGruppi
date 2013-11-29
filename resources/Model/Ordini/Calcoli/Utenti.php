<?php

/**
 * Description of Utenti
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Utenti 
    extends Model_Ordini_Ordine {
    
    
    function getTotaleByIduser($iduser) {
        $t = 0;
        if(isset($this->_arProd[$iduser]["prodotti"]) && count($this->_arProd[$iduser]["prodotti"]) > 0) {
            foreach ($this->_arProd[$iduser]["prodotti"] as $idprodotto => $objProd) {
                $t += $objProd->getTotale();
            }
        }
        return ($this->hasCostoSpedizione()) ? ($t + $this->getCostoSpedizioneRipartito()) : $t;
    }
    
    
    function getCostoSpedizioneRipartito() {
        return $this->getCostoSpedizione() / $this->getNum();
    }
    
    
    
    
    
    
    protected function setArray() {
        $ordObj = new Model_Ordini();
        $listOrd = $ordObj->getParzialiProdottiOrdinatiUtenti($this->_idordine);
        if(count($listOrd) > 0) {
            // Create instance Model_Ordini_Calcoli_Prodotto for any Product
            foreach ($listOrd as $value) {
                $iduser = $value->iduser;
                if(!isset($this->_arProd[$iduser])) {
                    $this->_arProd[$iduser] = array(
                        'nome' => $value->nome,
                        'cognome' => $value->cognome
                    );
                }
                $this->_arProd[$iduser]["prodotti"][$value->idprodotto] = new Model_Ordini_Calcoli_Prodotto($value);
            }
        }
        //Zend_Debug::dump($this->_arProd);die;
    }    
    
    
}
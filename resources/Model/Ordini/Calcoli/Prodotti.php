<?php

/**
 * Description of Utenti
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Prodotti 
    extends Model_Ordini_Ordine {
    
    private $_arProdProdotti = null;
    
    function getProdotti()
    {
        return $this->_getArProdotti();
    }
    
    
    
    function isThereSomeProductsOrdered() {
        if(count($this->getProdotti()) > 0) 
        {
            foreach ($this->getProdotti() as $idprodotto => $prodObj) 
            {
                $prodotto = $prodObj["prodotto"];
                if($prodotto->getQtaReale() > 0) 
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    
    
    
    
    function _getArProdotti() {
        if(is_null($this->_arProdProdotti)) 
        {
            if(count($this->_arProdOriginal) > 0) 
            {
                // Create instance Model_Ordini_Prodotto for any Product
                foreach ($this->_arProdOriginal as $value) 
                {
                    $idprodotto = $value->idprodotto;
                    if(!isset($this->_arProdProdotti[$idprodotto])) 
                    {
                        $this->_arProdProdotti[$idprodotto]["prodotto"] = new Model_Ordini_Prodotto($value);
                    } else {
                        // update Totale Prodotto
                        $this->_arProdProdotti[$idprodotto]["prodotto"]->addQtaReale($value->qta_reale);
                        $this->_arProdProdotti[$idprodotto]["prodotto"]->addQtaOrdinata($value->qta);
                    }
                    $this->_arProdProdotti[$idprodotto]["utenti"][$value->iduser] = array( 
                        'nome'    => $value->nome, 
                        'cognome' => $value->cognome,
                        'qta'     => $value->qta,
                        'qta_reale'=> $value->qta_reale
                    );
                }
            }
        }
        // Zend_Debug::dump($this->_arProd);die;
        return $this->_arProdProdotti;
    }    
    
    
}
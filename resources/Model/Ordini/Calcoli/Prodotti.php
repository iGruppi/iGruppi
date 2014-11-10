<?php

/**
 * Description of Utenti
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Prodotti  
    extends Model_Ordini_Calcoli_AbstractCalcoli {
    
    
    /**
     * Return bool IF some product is ordered
     * @return boolean
     */
    function isThereSomeProductsOrdered() {
        if(count($this->getProdotti()) > 0) 
        {
            foreach ($this->getProdotti() as $idprodotto => $prodotto) 
            {
                if($prodotto->getQtaReale() > 0) 
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    
}
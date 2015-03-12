<?php
/**
 * This class calculate TOTALI values
 * 
 * @author gullo
 */
class Model_Ordini_Calcoli_Totali 
    extends Model_Ordini_Calcoli_AbstractCalcoli {

    
    // TOTALE ORDINE (Senza costo di spedizione)
    public function getTotale() 
    {
        $t = 0;
        if(count($this->getProdotti()) > 0) 
        {
            foreach ($this->getProdotti() as $idprodotto => $objProd) 
            {
                if($objProd->isDisponibile())
                {
                    $t += $objProd->getTotale();
                }
            }
        }
        return $t;
    }
    
    // TOTALE INCLUSO SPEDIZIONE
    public function getTotaleConExtra() 
    {
        if($this->getSpeseExtra()->has()) 
        {
            $totaleExtra = 0;
            foreach($this->getSpeseExtra()->get() AS $extra)
            {
                $totaleExtra += $extra->getTotaleGruppo();
            }
            return ($this->getTotale() + $totaleExtra);
        } else {
            return $this->getTotale();
        }
    }
    
    public function getTotaleSenzaIva() 
    {
        $t = 0;
        if(count($this->getProdotti()) > 0) 
        {
            foreach ($this->getProdotti() as $idprodotto => $objProd) 
            {
                if($objProd->isDisponibile())
                {
                    $t += $objProd->getTotaleSenzaIva();
                }
            }
        }
        return $t;
    }
    
/*
 *  TODO:
 *  Questo metodo è da sistemare perchè con la nuova implementazione Pezzatura/Taglio e la qta_reale
 *  il numero risultante potrebbe avere dei decimali, il che non ha senso
 *  Conviene visualizzare un riepilogo basato sui vari Udm, es: Totale Confezioni, Totale Pezzi, Totale Kg, ecc.
 * 
 *  Per ora, per non visualizzare decimali, lascio la sommatoria dei qta (quantità ordinata)
 */    
    public function getTotaleColli() {
        $c = 0;
        if(count($this->getProdotti()) > 0) {
            foreach ($this->getProdotti() as $idprodotto => $objProd) {
                if($objProd->isDisponibile())
                {
                    $c += $objProd->getQta();
                }
            }
        }
        return $c;
    }
    
    public function isThereSomeProductsOrdered() {
        return (count($this->getTotale()) > 0) ? true : false;
    }
    
}
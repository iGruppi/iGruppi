<?php
/**
 * This is __OLD__ USER-ORDINE
 */
class Model_Prodotto_Mediator_UserOrdine
    extends Model_Prodotto_Mediator_Ordine
{

    /**
     * @return float
     */    
    public function addQtaReale($qta) {
        if($this->getQtaReale() > 0) {
            $this->getQtaReale() += (float)$qta;
        } else {
            $this->setQtaReale($qta);
        }
        return $this->getQtaReale();
    }
    
    /**
     * @return float
     */    
    public function getTotale() {
        return $this->getCostoOrdine() * $this->getQtaReale();
    }
    
    /**
     * @return float
     */    
    public function getTotaleSenzaIva() {
        return $this->getCostoSenzaIva() * $this->getQtaReale();
    }
    
    
    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        
    }
    
}

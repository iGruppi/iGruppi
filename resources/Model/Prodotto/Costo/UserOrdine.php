<?php
/**
 * This is __OLD__ USER-ORDINE
 */
class Model_Builder_Prodotto_Parts_UserOrdine
    extends Model_Builder_Prodotto_Parts_Ordine
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

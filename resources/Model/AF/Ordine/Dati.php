<?php
/**
 * This is a Concrete Product DATI for ORDINE
 */
class Model_AF_Ordine_Dati extends Model_AF_Dati
{

    
    /**
     * get Id Ordine
     * @return mixed
     */
    public function getIdOrdine() {
        return $this->_fValues->idordine;
    }
    
    
    function getCostoSpedizione() {
        return $this->_fValues->costo_spedizione;
    }
    
    function hasCostoSpedizione() {
        return ($this->getCostoSpedizione() > 0);
    }    
    /**
     * Save data to DB
     * @return bool
     */    
    public function saveToDB() {
        if($this->isChanged()) {
            return true;
        }
    }
}

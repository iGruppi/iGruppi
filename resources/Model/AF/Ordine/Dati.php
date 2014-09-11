<?php
/**
 * This is a Concrete Product DATI for ORDINE
 */
class Model_AF_Ordine_Dati extends Model_AF_Dati
{

    
    
    public function getStatus()
    {
        return new Model_Ordini_Status($this->getValues());
    }
    
    
    
/***************************
 *  GET METHODS
 */    
    
    /**
     * get ID ORDINE
     * @return string
     * @throws MyFw_Exception
     */
    public function getIdOrdine()
    {
        if(is_null($this->getValue("idordine"))) {
            throw new MyFw_Exception("IdOrdine is NOT set correctly!");
        }
        return $this->getValue("idordine");
    }
    
    
    function getCostoSpedizione() {
        return $this->getValue("costo_spedizione");
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

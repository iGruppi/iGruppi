<?php
/**
 * This is a PRODOTTO for LISTINO
 */
class Model_Builder_Prodotto_Parts_Listino
    extends Model_Builder_Prodotto_Parts_Prodotto
{
    
    /**
     * Verifica se il prodotto è nel Listino (se NON esiste il record in listini_prodotti idlistino è NULL!)
     * @return bool
     */
    public function isInListino() 
    {
        return is_null($this->getIdListino()) ? false : true;
    }
    
    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        
    }
    
}

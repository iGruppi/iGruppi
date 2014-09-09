<?php
/**
 * This is a Concrete Product DATI for ORDINE
 */
class Model_AF_Ordine_Dati extends Model_AF_Dati
{

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

<?php
/**
 * This is a Concrete Product DATI for ORDINE
 */
class Model_AF_Ordine_Dati extends Model_AF_Dati
{

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
    
    /**
     * Return DataInizio formatted by format
     * @return string
     */
    public function getDataInizio($format=null)
    {
        if(is_null($format))
        {
            return $this->getValue("data_inizio");
        } else {
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", $this->getValue("data_inizio"));
            return $dt->format($format);
        }
    }
    
    /**
     * Return DataFine formatted by format
     * @return string
     */
    public function getDataFine($format=null)
    {
        if(is_null($format))
        {
            return $this->getValue("data_fine");
        } else {
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", $this->getValue("data_fine"));
            return $dt->format($format);
        } 
    }
    
    /**
     * Return Condivisione
     * @return string
     */
    public function getCondivisione() {
        return $this->getValue("condivisione");
    }
    
    
    
    
/***************************
 *  GET METHODS
 */    
    
    /**
     * Set DataInizio, DateTime format
     * @param DateTime $v
     */
    public function setDataInizio($v)
    {
        $this->setValue("data_inizio", $v);
    }

    /**
     * Set DataFine, DateTime format
     * @param DateTime $v
     */
    public function setDataFine($v)
    {
        $this->setValue("data_fine", $v);
    }

    /**
     * Set Condivisione
     * @param string $v
     */
    public function setCondivisione($v)
    {
        $this->setValue("condivisione", $v);
    }
    
    
    /**
     * Save data to DB
     * @return bool
     */    
    public function saveToDB_Dati() 
    {
        if($this->isChanged()) {
            $db = Zend_Registry::get("db");
            // check for INSERT or UPDATE
            if(is_null($this->getValue("idordine")) ) {
                // INSERT, idordine does not exists
                $sth = $db->prepare("INSERT INTO ordini SET data_inizio= :data_inizio, data_fine= :data_fine, condivisione= :condivisione");
                $res = $sth->execute(array('data_inizio' => $this->getDataInizio(), 'data_fine' => $this->getDataFine(), 'condivisione' => $this->getCondivisione()));
                $this->setIdOrdine( $db->lastInsertId() );
                return $res;
            } else {
                // UPDATE ordini by idordine
                $sth = $db->prepare("UPDATE ordini SET data_inizio= :data_inizio, data_fine= :data_fine, condivisione= :condivisione WHERE idordine= :idordine");
                return $sth->execute(array('idordine' => $this->getIdOrdine(), 'data_inizio' => $this->getDataInizio(), 'data_fine' => $this->getDataFine(), 'condivisione' => $this->getCondivisione()));
            }
            // RESET isChanged flag
            $this->_isChanged = false;
        }
    }
}

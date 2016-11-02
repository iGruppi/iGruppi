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
     * Return Descrizione Ordine
     * @return string
     */
    public function getDescrizione() {
        return $this->getValue("descrizione");
    }
    
    /**
     * Return Condivisione
     * @return string
     */
    public function getCondivisione() {
        return $this->getValue("condivisione");
    }
    
    /**
     * Return iduser of Supervisore (field ordini.iduser_supervisore)
     * @return int
     */
    public function getSupervisore_IdUser()
    {
        return $this->getValue("iduser_supervisore");
    }
    
    /**
     * Return Name of Supervisore
     * @return string
     */
    public function getSupervisore_Name()
    {
        return $this->getValue("supervisore_name");
    }
    
    /**
     * Return Email of Supervisore
     * @return string
     */
    public function getSupervisore_Email()
    {
        return $this->getValue("supervisore_email");
    }
    
    /**
     * Return Tel of Supervisore
     * @return string
     */
    public function getSupervisore_Tel()
    {
        return $this->getValue("supervisore_tel");
    }
    
    /**
     * Return idgroup of the Supervisore
     * @return int
     */
    public function getSupervisore_IdGroup()
    {
        return $this->getValue("supervisore_idgroup");
    }

    /**
     * Return the Group Name of Supervisore
     * @return string
     */
    public function getSupervisore_GroupName()
    {
        return $this->getValue("supervisore_group");
    }
    
    /**
     * Return TRUE if is PRIVATO (Condivisione = PRI)
     * @return bool
     */
    public function isPrivato()
    {
        return ($this->getCondivisione() == "PRI") ? true : false;
    }
    
    /**
     * Return TRUE if it is SHARED (Condivisione = SHA)
     * @return bool
     */
    public function isCondiviso()
    {
        return ($this->getCondivisione() == "SHA") ? true : false;
    }

    /**
     * Return TRUE if it is PUBBLICO (Condivisione = PUB)
     * @return bool
     */
    public function isPubblico()
    {
        return ($this->getCondivisione() == "PUB") ? true : false;
    }
    
/***************************
 *  GET METHODS
 */    
    
    /**
     * set ID ORDINE
     * @param int $v idordine
     */
    public function setIdOrdine($v)
    {
        $this->setValue("idordine", $v);
    }    
    
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
     * Set Descrizione
     * @param string $v
     */
    public function setDescrizione($v)
    {
        $this->setValue("descrizione", $v);
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
     * @return boolean
     */    
    public function saveToDB_Dati() 
    {
        if($this->isChanged()) {
            // RESET isChanged flag
            $this->_isChanged = false;
            
            $db = Zend_Registry::get("db");
            // check for INSERT or UPDATE
            if(is_null($this->getValue("idordine")) ) {
                // GET IDUSER che apre l'ordine per impostare iduser_supervisore
                $iduser_supervisore = Zend_Auth::getInstance()->getIdentity()->iduser;
                // INSERT, idordine does not exists
                $sth = $db->prepare("INSERT INTO ordini SET iduser_supervisore= :iduser_supervisore, descrizione= :descrizione, data_inizio= :data_inizio, data_fine= :data_fine, condivisione= :condivisione");
                $res = $sth->execute(array('iduser_supervisore' => $iduser_supervisore, 'descrizione' => $this->getDescrizione(), 'data_inizio' => $this->getDataInizio(), 'data_fine' => $this->getDataFine(), 'condivisione' => $this->getCondivisione()));
                $this->setIdOrdine( $db->lastInsertId() );
                return $res;
            } else {
                // UPDATE ordini by idordine
                $sth = $db->prepare("UPDATE ordini SET descrizione= :descrizione, data_inizio= :data_inizio, data_fine= :data_fine, condivisione= :condivisione WHERE idordine= :idordine");
                return $sth->execute(array('idordine' => $this->getIdOrdine(), 'descrizione' => $this->getDescrizione(), 'data_inizio' => $this->getDataInizio(), 'data_fine' => $this->getDataFine(), 'condivisione' => $this->getCondivisione()));
            }
        }
        return true;
    }
}

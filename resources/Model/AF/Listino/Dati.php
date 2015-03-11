<?php
/**
 * This is a Concrete Product DATI for LISTINO
 */
class Model_AF_Listino_Dati extends Model_AF_Dati
{

    
/***************************
 *  GET METHODS
 */    
    
    /**
     * get ID LISTINO
     * @return string
     * @throws MyFw_Exception
     */
    public function getIdListino()
    {
        if(is_null($this->getValue("idlistino"))) {
            throw new MyFw_Exception("IdListino is NOT set correctly!");
        }
        return $this->getValue("idlistino");
    }
    
    /**
     * get ID PRODUTTORE
     * @return string
     */
    public function getIdProduttore()
    {
        return $this->getValue("idproduttore");
    }
    
    /**
     * get Nome Produttore
     * @return string
     */
    public function getProduttoreName()
    {
        return $this->getValue("ragsoc");
    }
    
    /**
     * get Descrizione Listino
     * @return string
     */
    public function getDescrizione()
    {
        return $this->getValue("descrizione");
    }
    
    /**
     * get Condivisione value
     * @return string
     */
    public function getCondivisione()
    {
        return $this->getValue("condivisione");
    }
    
    /**
     * Return NOME/COGNOME Referente produttore
     * @return string
     */
    public function getReferente_Nome()
    {
        return $this->getValue("nome")." ".$this->getValue("cognome");
    }
    
    /**
     * Return EMAIL Referente produttore
     * @return string
     */
    public function getReferente_Email()
    {
        return $this->getValue("email");
    }
    
    
/***************************
 *  SET METHODS
 */    

    /**
     * set ID LISTINO
     * @param int $v idlistino
     */
    public function setIdListino($v)
    {
        $this->setValue("idlistino", $v);
    }
    
    /**
     * set Descrizione field
     * @param string $v value descrizione
     */
    public function setDescrizione($v) 
    {
        $this->setValue("descrizione", $v);
    }
    
    /**
     * set IdProduttore field
     * @param mixed $v value idproduttore
     */
    public function setIdProduttore($v)
    {
        $this->setValue("idproduttore", $v);
    }
    
    /**
     * set Condivisione field
     * @param string $v value condivisione
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
            if(is_null($this->getValue("idlistino")) ) {
                // INSERT, idlistino does not exists
                $sth = $db->prepare("INSERT INTO listini SET descrizione= :descrizione, condivisione= :condivisione, idproduttore= :idproduttore, last_update=NOW()");
                $res = $sth->execute(array('idproduttore' => $this->getIdProduttore(), 'descrizione' => $this->getDescrizione(), 'condivisione' => $this->getCondivisione()));
                $this->setIdListino( $db->lastInsertId() );
                return $res;
            } else {
                // UPDATE listino by idlistino
                $sth = $db->prepare("UPDATE listini SET descrizione= :descrizione, condivisione= :condivisione, last_update=NOW() WHERE idlistino= :idlistino");
                return $sth->execute(array('idlistino' => $this->getIdListino(), 'descrizione' => $this->getDescrizione(), 'condivisione' => $this->getCondivisione()));
            }
            // RESET isChanged flag
            $this->_isChanged = false;
        }
    }
}

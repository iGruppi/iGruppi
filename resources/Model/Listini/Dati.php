<?php

/**
 * Description of Dati
 * 
 * @author gullo
 */
class Model_Listini_Dati {
    
    private $_fields = array(
        // listino table fields
        'idlistino',
        'descrizione',
        'condivisione',
        // produttore table fields
        'idproduttore',
        'ragsoc'
    );
    private $_fValues = array();
    private $_isChanged = false;
    
    /*
	* Overloading
	* __get
	*/
    public function __get($property)
    {
        if( in_array($property, $this->_fields)) {
            return ( isset($this->_fValues[$property]) ? $this->_fValues[$property] : null);
        } else {
            throw new Exception("Impossibile leggere la proprieta: $property");
        }
    }

	/*
	* Overloading
	* __set
	*/
    public function __set($property, $value)
    {
        if( in_array($property, $this->_fields)) {
            $this->_fValues[$property] = $value;
            $this->_isChanged = true;
        } else {
            throw new Exception("Impossibile scrivere la proprieta : $property");
        }
    }
    
    public function getIdListino()
    {
        return $this->idlistino;
    }
    
    public function getProduttoreName()
    {
        return $this->ragsoc;
    }
    
    public function getValues()
    {
        return $this->_fValues;
    }
    
    
    public function saveToDB()
    {
        if($this->_isChanged) {
            $db = Zend_Registry::get("db");
            // check for INSERT or UPDATE
            if( isset($this->_fValues["idlistino"]) ) {
                $sth = $db->prepare("UPDATE listini SET descrizione= :descrizione, condivisione= :condivisione, last_update=NOW() WHERE idlistino= :idlistino");
                return $sth->execute(array('idlistino' => $this->idlistino, 'descrizione' => $this->descrizione, 'condivisione' => $this->condivisione));
            } else {
                $sth = $db->prepare("INSERT INTO listini SET descrizione= :descrizione, condivisione= :condivisione, idproduttore= :idproduttore, last_update=NOW()");
                $res = $sth->execute(array('idproduttore' => $this->idproduttore, 'descrizione' => $this->descrizione, 'condivisione' => $this->condivisione));
                $this->idlistino = $db->lastInsertId();
                return $res;
            }
        }
    }
}

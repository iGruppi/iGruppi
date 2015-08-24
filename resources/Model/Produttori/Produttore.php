<?php
/**
 * Description of Produttore
 * 
 * @author Davide Gullo <gullo at m4ss.net>
 * 
 */
class Model_Produttori_Produttore {
    
    private $_values;
    private $_referenti;
    
    public function initByArrayValues(stdClass $values)
    {
        $this->_values = $values;
    }
    
    
    public function setReferenti(array $ref)
    {
        $this->_referenti = $ref;
    }
    
    public function getReferenti()
    {
        return $this->_referenti;
    }
    
    public function hasReferenti()
    {
        return count($this->_referenti);
    }
    
    
    public function __get($name) {
        if(isset($this->_values->$name))
        {
            return $this->_values->$name;
        }
        throw new Exception("Proprietà $name NOT EXISTS in " . __CLASS__);
    }
    
    public function isReferente($iduser)
    {
        return isset($this->_referenti[$iduser]);
    }
    
}

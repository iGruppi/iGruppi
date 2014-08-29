<?php
/**
 * Class Validita
 */
class Model_Builder_Parts_Data
{
    private $_dt = null;
    
    /**
     * @param mixed $dt
     *      -> string date format: YYYY-MM-dd
     *      -> Null (not set)
     */
    public function set($dt)
    {
        // set Inizio (DAL)
        if( !is_null($dt) ) {
            // add time to start correctly
            $dt = $dt . " 00:00:01";
            $this->_dt = new Zend_Date($dt, "YYYY-MM-dd HH:mm:ss");
        } else {
            $this->_dt = null;
        }
    }
    
    /**
     * @return mixed
     */        
    public function get($format)
    {
        if($this->isValid()) {
            return $this->_dt->toString($format);
        } else {
            return null;
        }
    }
    
    /**
     * @return bool
     */        
    public function isValid()
    {
        return !(is_null($this->_dt));
    }

    /**
     * @return string
     */        
    public function __toString() {
        if($this->isValid()) {
            return "Data: " . $this->_dt->toString("dd/MM/YYYY");
        } else {
            return "Nessuna data impostata (valore = NULL)";
        }
    }
}

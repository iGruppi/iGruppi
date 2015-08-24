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
            $this->_dt = DateTime::createFromFormat("Y-m-d H:i:s", $dt);
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
            return $this->_dt->format($format);
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
            return "Data: " . $this->_dt->format("d/m/Y");
        } else {
            return "Nessuna data impostata (valore = NULL)";
        }
    }
}

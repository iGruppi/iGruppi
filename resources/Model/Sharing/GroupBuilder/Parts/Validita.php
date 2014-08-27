<?php
/**
 * Class Validita
 */
class Model_Sharing_GroupBuilder_Parts_Validita
{
    private $_inizio = null;
    private $_fine = null;
    
    /**
     * @param mixed $inizio
     * @param mixed $fine
     *      -> string date format: YYYY-MM-dd
     *      -> Null (not set)
     */
    public function set($inizio, $fine)
    {
        // set Inizio (DAL)
        if( !is_null($inizio) ) {
            // add time to start correctly
            $inizio = $inizio . " 00:00:01";
            $this->_inizio = new Zend_Date($inizio, "YYYY-MM-dd HH:mm:ss");
        } else {
            $this->_inizio = null;
        }
        
        // set Fine (AL)
        if( !is_null($fine) ) {
            // add time to finish correctly
            $fine = $fine . " 23:59:59";
            $this->_fine = new Zend_Date($fine, "YYYY-MM-dd HH:mm:ss");
        } else {
            $this->_fine = null;
        }
    }
    
    /**
     * @return mixed
     */        
    public function getDal($format)
    {
        if($this->isSetValidita()) {
            return $this->_inizio->toString($format);
        } else {
            return null;
        }
    }
    
    /**
     * @return mixed
     */        
    public function getAl($format)
    {
        if($this->isSetValidita()) {
            return $this->_fine->toString($format);
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */        
    public function isValido()
    {
        if($this->isSetValidita()) {
            $now = new Zend_Date();
            return ( $this->_inizio->getTimestamp() <= $now->getTimestamp() && 
                     $this->_fine->getTimestamp() >= $now->getTimestamp()
                   );
        } else {
            // it is always valid
            return true;
        }
    }
    
    /**
     * @return bool
     */        
    public function isSetValidita()
    {
        return !(is_null($this->_inizio) && is_null($this->_fine));
    }

    /**
     * @return string
     */        
    public function __toString() {
        if($this->isSetValidita()) {
            return "Dal " . $this->_inizio->toString("dd/MM/YYYY") . " al " . $this->_fine->toString("dd/MM/YYYY");
        } else {
            return "Nessuna validita impostata (entrambi valori = NULL)";
        }
    }
}

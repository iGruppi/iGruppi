<?php
/**
 * Class Validita
 */
class Model_Builder_Parts_Validita
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
            $this->_inizio = DateTime::createFromFormat("Y-m-d H:i:s", $inizio);
        } else {
            $this->_inizio = null;
        }
        
        // set Fine (AL)
        if( !is_null($fine) ) {
            // add time to finish correctly
            $fine = $fine . " 23:59:59";
            $this->_fine = DateTime::createFromFormat("Y-m-d H:i:s", $fine);
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
            return $this->_inizio->format($format);
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
            return $this->_fine->format($format);
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
            return ( $this->_inizio->format("U") <= time() && 
                     $this->_fine->format("U") >= time()
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
            return "Dal " . $this->_inizio->format("d/m/Y") . " al " . $this->_fine->format("d/m/Y");
        } else {
            return "Nessuna validita impostata (entrambi valori = NULL)";
        }
    }
}

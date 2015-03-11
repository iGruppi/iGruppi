<?php
/**
 * Description of Spese Extra
 *
 * @author gullo
 */
class Model_Ordini_Extra_Spese {
    
    /**
     * List of all Extra Spese
     * @var array
     */
    private $_spese;
    
    public function set($str) {
        $spese = unserialize($str);
        if(is_array($spese) && count($spese) > 0)
        {
            foreach($spese AS $spesa)
            {
                $this->addSpesa(new Model_Ordini_Extra_Spesa($spesa[0], $spesa[1], $spesa[2]));
            }
        } else {
            $this->resetSpese();
        }
    }
    
    /**
     * Return list of ALL spese
     * @return array
     */
    public function get()
    {
        return $this->_spese;
    }
    
    /**
     * Return TRUE if there is some spesa
     * @return bool
     */
    public function has()
    {
        return (bool)count($this->_spese);
    }
    
    
    

    /**
     * Return array serialized of ALL spese extra
     * @return string
     */
    public function getSerializedArray()
    {
        $s = array();
        if(count($this->_spese) > 0)
        {
            foreach($this->_spese AS $spesa)
            {
                $s[] = array($spesa->getDescrizione(), $spesa->getCosto(), $spesa->getTipo());
            }
        }
        return serialize($s);
    }
    
    
    
    public function addSpesa(Model_Ordini_Extra_Spesa $spesa)
    {
        $this->_spese[] = $spesa;
    }
    
    public function resetSpese()
    {
        $this->_spese = array();
    }
}

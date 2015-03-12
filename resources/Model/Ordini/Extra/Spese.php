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
    
    /**
     * SET spese by Serialized Array (it should come from DB value)
     * @param string $str
     */
    public function set($str) {
        $spese = unserialize($str);
        if(is_array($spese) && count($spese) > 0)
        {
            foreach($spese AS $spesa)
            {
                $this->addSpesa($spesa[0], $spesa[1], $spesa[2]);
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
    
    /**
     * ADD Spesa by SpesaFactory
     * @param string $descrizione
     * @param float $costo
     * @param string $tipo
     */
    public function addSpesa($descrizione, $costo, $tipo)
    {
        $this->_spese[] = Model_Ordini_Extra_SpesaFactory::Create($descrizione, $costo, $tipo);
    }
    
    /**
     * Reset the _spese array
     * @return void
     */
    public function resetSpese()
    {
        $this->_spese = array();
    }
}

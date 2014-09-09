<?php
/**
 * This is the Abstract Product for DATI
 */
abstract class Model_AF_Dati implements Model_AF_AbstractProductInterface
{
    /**
     * @var stdClass
     */
    private $_dati;
    
    /**
     * @var bool
     */
    private $_isChanged = false;

    /**
     * @param mixed $values
     */
    public function initDatiByObject($values)
    {
        $this->_dati = $values;
    }
    
    /**
     * GET Value by Field
     * @param string $f the Field name
     * @return mixed
     */
    protected function getValue($f)
    {
        if(isset($this->_dati->$f))
        {
            return $this->_dati->$f;
        }
        return null;
    }
    
    /**
     * SET Value for Field
     * @param type $f Field name
     * @param type $v Value
     * @return void
     */
    protected function setValue($f, $v)
    {
        $this->_dati->$f = $v;
        $this->_isChanged = true;
    }
    
    /**
     * get isChanged flag
     * @return bool
     */
    protected function isChanged()
    {
        return $this->_isChanged;
    }
    
    /**
     * get All Values
     * @return stdClass
    */     
    public function getValues()
    {
        return $this->_dati;
    }
    
    /**
     * Save data to DB
     * @return bool
     */
    abstract public function saveToDB();
}

<?php
/**
 * This is the Abstract Product for DATI
 */
abstract class Model_AF_Dati extends Model_AF_AbstractHandlerCoR
{
    /**
     * @var stdClass
     */
    private $_dati;
    
    /**
     * @var bool
     */
    protected $_isChanged = false;
    
    
    public function __construct() {
        $this->_dati = new stdClass();
    }
    
    /**
     * @param mixed $values
     */
    public function initDati_ByObject($values)
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
    public function getDatiValues()
    {
        return $this->_dati;
    }
    
    /**
     * Save Dati to DB
     * @return bool
     */
    abstract public function saveToDB_Dati();
}

<?php
/**
 * Class Float number
 */
class Model_Builder_Parts_Float
{
    private $_float;
    
    /**
     * @param mixed $f
     */
    public function set($f)
    {
        $this->_float = (float)$f;
    }
    
    /**
     * @return float
     */        
    public function get()
    {
        return $this->_float;
    }
}

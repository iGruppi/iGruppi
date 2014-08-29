<?php
/**
 * Class Id
 */
class Model_Builder_Parts_Int
{
    private $_int;
    
    /**
     * @param mixed $id
     */
    public function set($int)
    {
        $this->_int = (int)$int;
    }
    
    /**
     * @return int
     */        
    public function get()
    {
        return $this->_int;
    }
}

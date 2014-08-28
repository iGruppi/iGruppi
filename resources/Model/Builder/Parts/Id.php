<?php
/**
 * Class Id
 */
class Model_Builder_Parts_Id
{
    private $_id;
    
    /**
     * @param mixed (string or int) $id
     */
    public function set($id)
    {
        $this->_id = (int)$id;
    }
    
    /**
     * @return int
     */        
    public function get()
    {
        return $this->_id;
    }
}

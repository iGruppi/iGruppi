<?php
/**
 * Class Id
 */
class Model_Builder_Parts_Id
{
    private $_id;
    
    /**
     * @param mixed $id
     */
    public function set($id)
    {
        $this->_id = $id;
    }
    
    /**
     * @return mixed
     */        
    public function get()
    {
        return $this->_id;
    }
}

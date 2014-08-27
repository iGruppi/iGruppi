<?php
/**
 * Class FlagSN
 */
class Model_Sharing_GroupBuilder_Parts_FlagSN
{
    private $_flag;
    
    /**
     * @param mixed $f
     */
    public function set($f)
    {
        if(is_string($f)) {
            $this->_flag = ($f == "S") ? true : false;
        } else if (is_bool($f)) {
            $this->_flag = $f;
        } else {
            $this->_flag = false;
        }
    }
    
    /**
     * @return bool
     */        
    public function getBool()
    {
        return (bool)$this->_flag;
    }

    /**
     * @return string
     */        
    public function getString()
    {
        return ($this->_flag) ? "S" : "N";
    }
    
}

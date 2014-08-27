<?php
/**
 * Class Text
 */
class Model_Sharing_GroupBuilder_Parts_Text
{
    private $_text;
    
    public function set($t)
    {
        $this->_text = (string)$t;
    }
    
    public function get()
    {
        return $this->_text;
    }
}

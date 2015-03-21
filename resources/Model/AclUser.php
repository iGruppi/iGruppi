<?php

class Model_AclUser
{
    // User roles for a specific GRUPPO
    private $_isFounder = false;
    private $_isContabile = false;
    
    function __construct($isFounder, $isContabile)
    {
        $this->_isFounder = ($isFounder == "S") ? true : false;
        $this->_isContabile = ($isContabile == "S") ? true : false;
    }
    
    /**
     * Boolean flag for Founder
     * @return bool
     */
    private function _isFounder()
    {
        return $this->_isFounder;
    }
    
    /**
     * Boolean flag for Contabile
     * @return bool
     */
    private function _isContabile()
    {
        return $this->_isContabile;
    }
    
/*********************
 *  PERMISSIONS
 */    
    
    /**
     * Can modify Users of the group
     * @return bool
     */
    public function canModifyUser()
    {
        return $this->_isFounder();
    }
    
    /**
     * Can manage Ordini
     * @return bool
     */
    public function canManageOrdini()
    {
        return $this->_isFounder();
    }
    
    /**
     * Only the Tesoriere can manage CASSA
     * @return bool
     */
    public function canManageCassa()
    {
        return $this->_isContabile();
    }
    
    /**
     * Only the Tesoriere can close the ORDER
     * @return bool
     */
    public function canCloseOrder()
    {
        return $this->_isContabile();
    }
    
}
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
    public function isFounder()
    {
        return $this->_isFounder;
    }
    
    /**
     * Boolean flag for Contabile
     * @return bool
     */
    public function isContabile()
    {
        return $this->_isContabile;
    }
    
/*********************
 *  WARNING! *********
 * 
 *  I seguenti metodi sono destinati a sparire perchè VANNO implementati nella varie classi (Ordini, Listini, ecc).
 *  Su questa classe effettuare solo le chiamate ai metodi SOPRA!
 * 
 *  WARNING! *********
 *********************/    
    
    /**
     * Can modify Users of the group
     * @return bool
     */
    public function canModifyUser()
    {
        return $this->isFounder();
    }
    
    /**
     * Only the Tesoriere can manage CASSA
     * @return bool
     */
    public function canManageCassa()
    {
        return $this->isContabile();
    }
    
}
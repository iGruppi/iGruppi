<?php

/**
 * Description of Listino
 * 
 * @author gullo
 */
class Model_Listini_Listino {
    
    private $_dati = null;
    private $_groups = null;
    private $_prodotti = null;
    private $_categories = null;
    
    
/*  **************************************************************************
 *  DATI LISTINO
 */    
    
    public function getDati()
    {
        if(is_null($this->_dati)) {
            $this->_dati = new Model_Listini_Dati();
        }
        return $this->_dati;
    }
    
    
/*  **************************************************************************
 *  PERMISSION
 */    
    
    private function isReferenteProduttore()
    {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        return $userSessionVal->refObject->is_Referente($this->getDati()->idproduttore);
    }
    
    private function isOwner()
    {
        return ($this->getGroups()->getMyIdGroup() == $this->getGroups()->getMasterGroup()->getIdGroup());
    }
    
    public function canManageListino()
    {
        return $this->isReferenteProduttore();
    }
    
    public function canManageCondivisione()
    {
        return $this->isOwner();
    }
    
    
/*  **************************************************************************
 *  CATEGORIES METHODS
 */
    private function _getCategories()
    {
        if( is_null($this->_categories) ) {
            throw new MyFw_Exception("Categories array is not SET!");
        }
        return $this->_categories;
    }
    
    public function setCategories($c)
    {
        $this->_categories = $c;
    }
    
    public function getCategories()
    {
        try {
            $cats = $this->_getCategories();
            if(count($cats) > 0) {
                $myCat = array();
                foreach ($cats as $value) {
                    $myCat[] = $value->descrizione;
                }
                return implode(", ", $myCat);
            } else {
                return null;
            }
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
    }
    
/*  **************************************************************************
 *  GROUPS object
 */    
    public function getGroups()
    {
        if( is_null($this->_groups) ) {
            $this->_groups = new Model_Listini_Groups();
        }
        return $this->_groups;
    }

/*  **************************************************************************
 *  PRODOTTI Object
 */    
    
    public function getProdotti()
    {
        if(is_null($this->_prodotti)) {
            $this->_prodotti = new Model_Listini_Prodotti();
        }
        return $this->_prodotti;
    }
    

    
/*  **************************************************************************
 *  SAVE CHANGES TO DB
 */    
    
    public function save()
    {
        // save Dati
        $res1 = $this->getDati()->saveToDB();
        // save Groups
        $res2 = $this->getGroups()->saveToDB();
        
        return ($res1 && $res2);
    }
    
}

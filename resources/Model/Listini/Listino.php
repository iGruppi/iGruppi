<?php

/**
 * Description of Listino
 * 
 * @author gullo
 */
class Model_Listini_Listino {
    
    private $_mygroup = null;
    private $_dati = null;
    private $_groups = null;
    private $_categories = null;
    
    
    public function setMyIdGroup($idgroup)
    {
        $this->_mygroup = $idgroup;
    }
    
    public function getMyIdGroup()
    {
        if( is_null($this->_mygroup) ) {
            throw new Exception("IdGroup of 'MyGroup' is not SET!");
        }
        return $this->_mygroup;
    }
    
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
        return ($this->getMyIdGroup() == $this->getGroups()->getMasterGroup()->getIdGroup());
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
            throw new Exception("Categories array is not SET!");
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
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    
/*  **************************************************************************
 *  GROUPS METHODS
 */    
    public function getGroups()
    {
        if( is_null($this->_groups) ) {
            $this->_groups = new Model_Listini_Groups();
        }
        return $this->_groups;
    }
    
    public function getMyGroup()
    {
        return $this->getGroups()->getGroupByIdGroup($this->getMyIdGroup());
    }

    /**
     * @return void
     * @param array $groups Array of idgroup values
     */
    public function resetGroups(array $groups=array()) {
        // check the condivisione type
        if( $this->getDati()->condivisione != "SHA" )
        {
            $groups = array();
        }
        // add My Group to $groups array because there must be ALMOST one group in listini_groups table
        if(!in_array($this->getMyIdGroup(), $groups)) {
            $groups[] = $this->getMyIdGroup();
        }
        $this->getGroups()->resetGroupsByArray($groups);
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

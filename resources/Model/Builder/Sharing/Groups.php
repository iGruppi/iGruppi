<?php

/**
 * Description of Sharing_Groups
 * 
 * @author gullo
 */
class Model_Builder_Sharing_Groups {
    
    private $_mygroup = null;
    private $_idgroup_master = null;
    private $_groups = array();
    

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
    
    public function getMyGroup()
    {
        return $this->getGroupByIdGroup($this->getMyIdGroup());
    }

    
    public function getMasterGroup()
    {   
        if(is_null($this->_idgroup_master) || !$this->issetGroup($this->_idgroup_master))
        {
            throw new Exception("idgroup_master is NOT set or that groups does NOT exists!");
        }
        return $this->getGroupByIdGroup($this->_idgroup_master);
    }
    
    public function getGroupByIdGroup($idgroup)
    {
        if( isset($this->_groups[$idgroup])) {
            return $this->_groups[$idgroup];
        }
        return null;
    }
    
    protected function getAllGroups()
    {
        return $this->_groups;
    }



    /*
    MANAGE GROUPS ARRAY
*/
    
    /**
     * @return bool
     */
    public function issetGroup($idgroup)
    {
        return isset($this->_groups[$idgroup]);
    }
    
    /**
     * @return array
     */
    public function addGroup(stdClass $g)
    {
        $idgroup = $g->idgroup_slave;
        if( !$this->issetGroup($idgroup) ) {
            $this->_groups[$idgroup] = $this->_createGroup($g);
        }
        return $this->_groups[$idgroup];
    }
    
    /**
     * @return Model_Sharing_GroupBuilder_Parts_Group
     */
    private function _createGroup(stdClass $g)
    {
        $builderGroup = new Model_Builder_Sharing_Group_ListinoBuilder();
        $director = new Model_Builder_Sharing_Group_Director();
        $group = $director->build($builderGroup);
        $group->setId($g->id);
        $group->setIdGroupMaster($g->idgroup_master);
        $group->setIdGroup($g->idgroup_slave);
        // Check and set Default values for other fields
        $group->setGroupName( (isset($g->group_nome) ? $g->group_nome : "") );
        $group->setRefIdUser( (isset($g->ref_iduser) ? $g->ref_iduser : 0) );
        $group->setRefNome(   (isset($g->ref_nome) ? $g->ref_nome : ""), (isset($g->ref_cognome) ? $g->ref_cognome : "") );
        $group->setValidita(  (isset($g->valido_dal) ? $g->valido_dal : null), (isset($g->valido_al) ? $g->valido_al : null) );
        $group->setVisibile(  (isset($g->visibile) ? $g->visibile : "N") );
        
        // set idmaster_group (it should be the same for all the slaves groups!)
        $this->_idgroup_master = $g->idgroup_master;
        
        return $group;
    }
    
    /**
     * @return void
     */    
    public function removeGroup_ByIdGroup($idgroup)
    {
        if( $this->issetGroup($idgroup) ) {
            unset($this->_groups[$idgroup]);
        }
    }

    
    /**
     * @return void
     * @param string $condivisione Condivisione type, it could be: PRI, PUB or SHA 
     * @param array $groups Array of "idgroup" values to reset $this->_groups 
     *  If a group does not exists it creates a new one by KEYS (idlistino, idgroup_master, idgroup_slave) and default values for other fields
     *  If a group already exists it keeps the old data
     */
    public function resetGroups($condivisione, array $groups=array()) {
        // check the condivisione type
        if( $condivisione != "SHA" )
        {
            $groups = array();
        }
        // add My Group to $groups array because there must be ALMOST one group in listini_groups table
        if(!in_array($this->getMyIdGroup(), $groups)) {
            $groups[] = $this->getMyIdGroup();
        }
        // start to reset array groups
        $newArray = array();
        foreach ($groups AS $idgroup) {
            if($this->issetGroup($idgroup)) {
                $newArray[$idgroup] = $this->getGroupByIdGroup($idgroup);
            } else {
                $group = new stdClass();
                $group->id = $this->getMasterGroup()->getId();
                $group->idgroup_master = $this->getMasterGroup()->getIdGroup();
                $group->idgroup_slave = $idgroup;
                $newArray[$idgroup] = $this->_createGroup($group);
            }
        }
        $this->_groups = $newArray;
    }
    
    /**
     * @return void
     * @param array $groups Array groups to init the class (every group has to be a stdClass)
     */    
    public function initGroupsByArray($groups)
    {
        // set all groups
        if(is_array($groups) && count($groups) > 0)
        {
            foreach($groups AS $group)
            {
                // Add the group to _groups array
                $this->addGroup($group);
            }
        } else {
            throw new Exception("Groups array is not correctly initializated!");
        }
    }

}

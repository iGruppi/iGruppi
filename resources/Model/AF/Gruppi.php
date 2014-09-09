<?php
/**
 * This is the Abstract Product for GRUPPI
 */
abstract class Model_AF_Gruppi implements Model_AF_AbstractProductInterface
{

    /**
     *
     * @var type 
     */
    private $_mygroup = null;
    private $_idgroup_master = null;
    private $_groups = array();

    /**
     * set My Id Group (it's the Group of the user session)
     * @param $idgroup
     */
    public function setMyIdGroup($idgroup)
    {
        $this->_mygroup = $idgroup;
    }
    
    public function getMyIdGroup()
    {
        if( is_null($this->_mygroup) ) {
            throw new MyFw_Exception("IdGroup of 'MyGroup' is not SET!");
        }
        return $this->_mygroup;
    }
    
    public function getMyGroup()
    {
        return $this->getGroupByIdGroup($this->getMyIdGroup());
    }

    /**
     * @return void
     * @param array $groups Array groups to init the class (every group has to be a stdClass)
     */
    public function initDatiByObject($groups)
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
            throw new MyFw_Exception("Groups array is not correctly initializated!");
        }
    }


    /**
     * @return null
     * @throws MyFw_Exception
     */
    public function getMasterGroup()
    {   
        if(is_null($this->_idgroup_master) || !$this->issetGroup($this->_idgroup_master))
        {
            throw new MyFw_Exception("idgroup_master is NOT set or that groups does NOT exists!");
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
     * @return Model_Builder_Sharing_Group_Parts_Group 
     *      return the group created
     */
    public function addGroup(stdClass $g)
    {
        try {
            $idgroup = $g->idgroup_slave;
            if( !$this->issetGroup($idgroup) ) {
                $this->_groups[$idgroup] = $this->_createGroup($g);
            }
            return $this->_groups[$idgroup];
            
        } catch (MyFw_Exception $exc) {
            $exc->displayError();
        }
    }
    
    /**
     *  @param stdClass $g is a Group object data
     *  @return Model_Builder_Sharing_Group_Parts_Group 
     */
    private function _createGroup(stdClass $g)
    {
        // check mandatory fields
        if( !isset($g->id) || !isset($g->idgroup_master) || !isset($g->idgroup_slave)) {
            throw new MyFw_Exception('Cannot build a group, miss some data!');
        }
        // build a group and set data
        $group = $this->buildGroup();
        $group->setId($g->id);
        $group->setIdGroupMaster($g->idgroup_master);
        $group->setIdGroup($g->idgroup_slave);
        // Check and set Default values for ALL the others fields
        $group->setGroupName(   (isset($g->group_nome) ? $g->group_nome : "") );
        $group->setRefIdUser(   (isset($g->ref_iduser) ? $g->ref_iduser : 0) );
        $group->setRefNome(     (isset($g->ref_nome) ? $g->ref_nome : ""), (isset($g->ref_cognome) ? $g->ref_cognome : "") );
        $group->setVisibile(    (isset($g->visibile) ? $g->visibile : "N") );
        $group->setValidita(    (isset($g->valido_dal) ? $g->valido_dal : null), (isset($g->valido_al) ? $g->valido_al : null) );
        $group->setNoteConsegna((isset($g->note_consegna) ? $g->note_consegna : "") );
        
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
     *  If a group does not exists it creates a new one by KEYS (id, idgroup_master, idgroup_slave) and default values for other fields
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
                try {
                    // try to create a new group with default values
                    $group = new stdClass();
                    $group->id = $this->getMasterGroup()->getId();
                    $group->idgroup_master = $this->getMasterGroup()->getIdGroup();
                    $group->idgroup_slave = $idgroup;
                    $newArray[$idgroup] = $this->_createGroup($group);
                            
                } catch (MyFw_Exception $exc) {
                    $exc->displayError();
                }
            }
        }
        $this->_groups = $newArray;
    }
    
}

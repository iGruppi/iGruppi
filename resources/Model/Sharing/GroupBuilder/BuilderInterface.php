<?php
/**
 *
 */
abstract class Model_Sharing_GroupBuilder_BuilderInterface
{
    /**
     * @var Parts\Group
     */
    protected $group;

    /**
     * @return void
     */
    public function createGroup(){ }

    /**
     * @return void
     */
    public function addKeys()
    {
        $this->group->setPart('idlistino', new Model_Sharing_GroupBuilder_Parts_Id());
        $this->group->setPart('idgroup_master', new Model_Sharing_GroupBuilder_Parts_Id());
        $this->group->setPart('idgroup_slave', new Model_Sharing_GroupBuilder_Parts_Id());
    }
    
    /**
     * @return void
     */
    public function addGroupName()
    {
        $this->group->setPart('group_nome', new Model_Sharing_GroupBuilder_Parts_Text());
    }

    /**
     * @return void
     */
    public function addRef()
    {
        $this->group->setPart('ref_iduser', new Model_Sharing_GroupBuilder_Parts_Id());
        $this->group->setPart('ref_nome', new Model_Sharing_GroupBuilder_Parts_Text());
        $this->group->setPart('ref_cognome', new Model_Sharing_GroupBuilder_Parts_Text());
    }
    
    /**
     * @return void
     */
    public function addValidita()
    {
        $this->group->setPart('validita', new Model_Sharing_GroupBuilder_Parts_Validita());
    }

    /**
     * @return void
     */
    public function addVisibile()
    {
        $this->group->setPart('visibile', new Model_Sharing_GroupBuilder_Parts_FlagSN());
    }
    
    /**
     * @return void
     */
    public function addNoteConsegna(){ }

    /**
     * @return Parts\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}

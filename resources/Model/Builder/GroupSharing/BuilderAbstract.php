<?php
/**
 *
 */
abstract class Model_Builder_GroupSharing_BuilderAbstract
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
    public function addId()
    {
        $this->group->setPart('id', new Model_Builder_Parts_Id());
    }
        
    /**
     * @return void
     */
    public function addGroupKeys()
    {
        $this->group->setPart('idgroup_master', new Model_Builder_Parts_Id());
        $this->group->setPart('idgroup_slave', new Model_Builder_Parts_Id());
    }
    
    /**
     * @return void
     */
    public function addGroupName()
    {
        $this->group->setPart('group_nome', new Model_Builder_Parts_Text());
    }

    /**
     * @return void
     */
    public function addRef()
    {
        $this->group->setPart('ref_iduser', new Model_Builder_Parts_Id());
        $this->group->setPart('ref_nome', new Model_Builder_Parts_Text());
        $this->group->setPart('ref_cognome', new Model_Builder_Parts_Text());
    }
    
    /**
     * @return void
     */
    public function addValidita()
    {
        $this->group->setPart('validita', new Model_Builder_Parts_Validita());
    }

    /**
     * @return void
     */
    public function addVisibile()
    {
        $this->group->setPart('visibile', new Model_Builder_Parts_FlagSN());
    }
    
    /**
     * @return void
     */
    public function addExtra(){ 
        $this->group->setPart('extra', new Model_Ordini_Extra_Spese(""));        
    }
    
    /**
     * @return void
     */
    public function addNoteConsegna(){ 
        $this->group->setPart('note_consegna', new Model_Builder_Parts_Text());        
    }

    /**
     * @return Parts\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}

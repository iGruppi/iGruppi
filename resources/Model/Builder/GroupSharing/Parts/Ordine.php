<?php
/**
 * Ordine is a group for ordini sharing environment
 */
class Model_Builder_GroupSharing_Parts_Ordine 
    extends Model_Builder_GroupSharing_Parts_Group
{

    
    public function isSetUserRef()
    {
        return !is_null($this->getIdUser_Incaricato());
    }
    
    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        $ar = array(
            'idordine'          => $this->getId(),
            'idgroup_master'    => $this->getIdGroupMaster(),
            'idgroup_slave'     => $this->getIdGroup(),
            'iduser_incaricato' => $this->getIdUser_Incaricato(),
            'visibile'          => $this->getVisibile()->getString(),
            'note_consegna'     => $this->getNoteConsegna(),
            'extra'             => $this->getExtra()->getSerializedArray()
        );
        return $ar;
    }
    
}

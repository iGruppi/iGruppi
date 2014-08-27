<?php
/**
 * Ordine is a group for ordini sharing environment
 */
class Model_Sharing_GroupBuilder_Parts_Ordine 
    extends Model_Sharing_GroupBuilder_Parts_Group
{
    /**
     * @param string $note
     */
    public function setNoteConsegna($note)
    {
        $this->data["note_consegna"]->set($note);
    }
    
    /**
     * @return string
     */    
    public function getNoteConsegna()
    {
        return $this->data["note_consegna"]->get();
    }
    
    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        $ar = array(
            'idlistino'         => $this->getIdListino(),
            'idgroup_master'    => $this->getIdGroupMaster(),
            'idgroup_slave'     => $this->getIdGroup(),
            'iduser_ref'        => $this->getRefIdUser(),
            'visibile'          => $this->getVisibile()->getString(),
            'note_consegna'     => $this->getNoteConsegna()
        );
        return $ar;
    }
    
}

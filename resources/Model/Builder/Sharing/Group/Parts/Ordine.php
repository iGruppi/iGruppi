<?php
/**
 * Ordine is a group for ordini sharing environment
 */
class Model_Builder_Sharing_Group_Parts_Ordine 
    extends Model_Builder_Sharing_Group_Parts_Group
{

    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        $ar = array(
            'idordine'         => $this->getId(),
            'idgroup_master'    => $this->getIdGroupMaster(),
            'idgroup_slave'     => $this->getIdGroup(),
            'iduser_ref'        => $this->getRefIdUser(),
            'visibile'          => $this->getVisibile()->getString(),
            'note_consegna'     => $this->getNoteConsegna()
        );
        return $ar;
    }
    
}

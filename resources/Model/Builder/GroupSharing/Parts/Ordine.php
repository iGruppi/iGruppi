<?php
/**
 * Ordine is a group for ordini sharing environment
 */
class Model_Builder_GroupSharing_Parts_Ordine 
    extends Model_Builder_GroupSharing_Parts_Group
{

    /**
     * This field does not exists in ORDINE
     */
    public function setValidita($dal, $al) {}
    public function getValidita() {}
    
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

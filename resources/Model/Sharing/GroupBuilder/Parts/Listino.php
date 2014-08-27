<?php
/**
 * Listino is a group for listini sharing environment
 */
class Model_Sharing_GroupBuilder_Parts_Listino 
    extends Model_Sharing_GroupBuilder_Parts_Group
{
    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        $ar = array(
            'idlistino'         => $this->getIdListino(),
            'idgroup_master'    => $this->getIdGroupMaster(),
            'idgroup_slave'     => $this->getIdGroup(),
            'valido_dal'        => $this->getValidita()->getDal("YYYY-MM-dd"),
            'valido_al'         => $this->getValidita()->getAl("YYYY-MM-dd"),
            'visibile'          => $this->getVisibile()->getString()
        );
        return $ar;
    }
    
}

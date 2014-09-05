<?php
/**
 * Listino is a group for listini sharing environment
 */
class Model_Builder_Sharing_Group_Parts_Listino 
    extends Model_Builder_Sharing_Group_Parts_Group
{
    
    /**
     * This field does not exists in LISTINO
     */
    public function setNoteConsegna($note) {}
    public function getNoteConsegna() {}

    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        $ar = array(
            'idlistino'         => $this->getId(),
            'idgroup_master'    => $this->getIdGroupMaster(),
            'idgroup_slave'     => $this->getIdGroup(),
            'valido_dal'        => $this->getValidita()->getDal(MyFw_Form_Filters_Date::_MYFORMAT_DATE_DB),
            'valido_al'         => $this->getValidita()->getAl(MyFw_Form_Filters_Date::_MYFORMAT_DATE_DB),
            'visibile'          => $this->getVisibile()->getString()
        );
        return $ar;
    }
    
}

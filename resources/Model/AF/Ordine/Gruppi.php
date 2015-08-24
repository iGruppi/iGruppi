<?php
/**
 * This is a Concrete Product GRUPPI for ORDINE
 */
class Model_AF_Ordine_Gruppi extends Model_AF_Gruppi
{
 
    
    /**
     * @return Model_Builder_GroupSharing_Parts_Ordine
     */
    public function buildGroup()
    {
        $builderGroup = new Model_Builder_GroupSharing_OrdineBuilder();
        $director = new Model_Builder_GroupSharing_Director();
        $group = $director->build($builderGroup);
        return $group;
    }

    
    /**
     * Save data to DB
     * @return bool
     */    
    public function saveToDB_Gruppi()
    {
        $db = Zend_Registry::get("db");
        $db->beginTransaction();
        // REMOVE alla groups from ordini_groups
        $idgroup_master = $this->getMasterGroup()->getIdGroup();
        $idordine = $this->getMasterGroup()->getId();
        $resd = $db->query("DELETE FROM ordini_groups WHERE idordine='$idordine' AND idgroup_master='$idgroup_master'");
        if(!$resd) {
            $db->rollBack();
            return false;
        }
        // INSERT groups
        $sth_insert = $db->prepare("INSERT INTO ordini_groups SET idordine= :idordine, idgroup_master= :idgroup_master, idgroup_slave= :idgroup_slave, iduser_ref= :iduser_ref, note_consegna= :note_consegna, visibile= :visibile, extra= :extra");
        foreach($this->getAllGroups() AS $group) {
            $res = $sth_insert->execute($group->dumpValuesForDB());
            if(!$res) {
                $db->rollBack();
                return false;
            }
        }
        return $db->commit();
    }

}

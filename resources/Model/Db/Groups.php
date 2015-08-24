<?php

/**
 * Description of Model_Db_Groups
 * 
 * @author gullo
 */
class Model_Db_Groups extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * Get All groups
     * @param $excludeMyGroup (TRUE = exclude my group)
     * @return null|array
     */
    function getAll($excludeMyGroup = false) {
        if($excludeMyGroup) {
            $userSessionVal = new Zend_Session_Namespace('userSessionVal');
            $sth = $this->db->prepare("SELECT * FROM groups WHERE idgroup != :idgroup ORDER BY nome ASC");
            $sth->execute(array('idgroup' => $userSessionVal->idgroup));
        } else {
            $sth = $this->db->prepare("SELECT * FROM groups ORDER BY nome ASC");
            $sth->execute();
        }
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    function getGroupById($idgroup) {
        $sth = $this->db->prepare("SELECT * FROM groups WHERE idgroup= :idgroup");
        $sth->execute(array('idgroup' => $idgroup));
        return $sth->fetch(PDO::FETCH_OBJ);
    }

    function getGroupFoundersById($idgroup) {
        $sql = "SELECT ug.*, u.email "
              ."FROM users_group AS ug "
              ."LEFT JOIN users AS u ON ug.iduser=u.iduser "
              ."WHERE ug.fondatore='S' AND ug.idgroup= :idgroup";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;
    }
     
}
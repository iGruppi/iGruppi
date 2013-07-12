<?php

/**
 * Description of Model_Groups
 * 
 * @author gullo
 */
class Model_Groups extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }

    function getAll() {
        $sth = $this->db->prepare("SELECT * FROM groups WHERE 1 ORDER BY nome ASC");
        $sth->execute();
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return null;
    }

    function getGroupById($idgroup) {

        $sth_app = $this->db->prepare("SELECT g.*, u.email FROM groups AS g LEFT JOIN users AS u ON g.idfondatore=u.iduser WHERE g.idgroup= :idgroup");
        $sth_app->execute(array('idgroup' => $idgroup));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }

    
}
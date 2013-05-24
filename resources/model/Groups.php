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
    
}
<?php

/**
 * Description of Model_Users
 * 
 * @author gullo
 */
class Model_Users extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    
    function getAppsByIdUser($iduser) {
        $sql = "SELECT * FROM apps AS a"
              ." LEFT JOIN users_apps AS ua ON a.idapp=ua.idapp"
              ." WHERE ua.iduser= :iduser"
              ." ORDER BY a.appName";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

}
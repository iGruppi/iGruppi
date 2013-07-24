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
    
    function getUserByIdInGroup($iduser, $idgroup) {

        $sth_app = $this->db->prepare("SELECT * FROM users AS u LEFT JOIN users_group AS ug ON u.iduser=ug.iduser WHERE ug.iduser= :iduser AND ug.idgroup= :idgroup");
        $sth_app->execute(array('iduser' => $iduser, 'idgroup' => $idgroup));
        return $sth_app->fetch(PDO::FETCH_ASSOC);
    }
    

}
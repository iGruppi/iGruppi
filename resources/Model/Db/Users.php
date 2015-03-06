<?php

/**
 * Description of Model_Db_Users
 * 
 * @author gullo
 */
class Model_Db_Users extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    function getUserByIdInGroup($iduser, $idgroup) {

        $sth_app = $this->db->prepare("SELECT * FROM users AS u LEFT JOIN users_group AS ug ON u.iduser=ug.iduser WHERE ug.iduser= :iduser AND ug.idgroup= :idgroup");
        $sth_app->execute(array('iduser' => $iduser, 'idgroup' => $idgroup));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }
    
    function getUsersByIdGroup($idgroup, $attivi=null) {
        // get All Iscritti in Group
        $sql = "SELECT u.*, ug.attivo, CONCAT(u.nome, ' ', u.cognome) AS nominativo "
              ." FROM users_group AS ug"
              ." LEFT JOIN users AS u ON ug.iduser=u.iduser"
              ." WHERE ug.idgroup= :idgroup";
        if(is_bool($attivi)) {
            $sql .= ($attivi) ? " AND ug.attivo = 'S'" : " AND ug.attivo = 'N'";
        }
        $sql .= " ORDER BY u.cognome";
        //echo $sql; die;
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        return $sth->fetchAll(PDO::FETCH_CLASS);
    }
    
    function getRefByIduserAndIdgroup($iduser, $idgroup) 
    {
        $sql = "SELECT idproduttore FROM referenti WHERE iduser_ref= :iduser AND idgroup= :idgroup";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser, 'idgroup' => $idgroup));
        return $sth->fetchAll(PDO::FETCH_CLASS);
    }

    function getGlobalRefByIduser($iduser) 
    {
        $sql = "SELECT idproduttore, livello FROM users_produttori WHERE iduser= :iduser";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser));
        return $sth->fetchAll(PDO::FETCH_CLASS);
    }
    
}
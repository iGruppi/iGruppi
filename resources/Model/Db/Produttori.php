<?php

/**
 * Description of Model_Db_Produttori
 * 
 * @author gullo
 */
class Model_Db_Produttori extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    function getProduttoriByIdRef($iduser)
    {
        $sql = "SELECT p.ragsoc, p.idproduttore "
              ." FROM produttori AS p"
              ." LEFT JOIN referenti AS r ON p.idproduttore=r.idproduttore"
              ." WHERE r.iduser_ref= :iduser"
              ." ORDER BY p.ragsoc";
        $sth_app = $this->db->prepare($sql);
        $sth_app->execute(array('iduser' => $iduser));
        return $sth_app->fetchAll(PDO::FETCH_OBJ);        
    }

    
    function getProduttoreById($idproduttore) {
        $sql = "SELECT * FROM produttori WHERE idproduttore= :idproduttore";
        $sth_app = $this->db->prepare($sql);
        $sth_app->execute(array('idproduttore' => $idproduttore));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }

    function getProduttoriByIdGroup($idgroup) {
        $sql = "SELECT p.*, r.iduser_ref AS ref_int_iduser, u.nome AS ref_int_nome, u.cognome AS ref_int_cognome, u.email AS ref_int_email,"
              ." upr.iduser AS ref_ext_iduser, upr.nome AS ref_ext_nome, upr.cognome AS ref_ext_cognome, upr.email AS ref_ext_email "
              ." FROM produttori AS p"
              ." LEFT OUTER JOIN referenti AS r ON p.idproduttore=r.idproduttore AND r.idgroup= :idgroup"
              ." LEFT OUTER JOIN users AS u ON r.iduser_ref=u.iduser"
              ." LEFT OUTER JOIN users_produttori AS up ON p.idproduttore=up.idproduttore"
              ." LEFT OUTER JOIN users AS upr ON upr.iduser=up.iduser"
              ." ORDER BY p.ragsoc";
        $sth_app = $this->db->prepare($sql);
        $sth_app->execute(array('idgroup' => $idgroup));
        return $sth_app->fetchAll(PDO::FETCH_OBJ);        
    }

}
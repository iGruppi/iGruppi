<?php

/**
 * Description of Model_Produttori
 * 
 * @author gullo
 */
class Model_Produttori extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }

    
    function getProduttoreById($idp, $idgroup) {
        /*
         * Utilizzo idgroup come filtro per i furbi
         * In questo modo non possono visualizzare Produttori di altri Gruppi cambiando l'idproduttore
         */
        
        $sql = "SELECT * FROM produttori AS p"
              ." LEFT JOIN groups_produttori AS gp ON p.idproduttore=gp.idproduttore"
              ." WHERE gp.idproduttore= :idproduttore"
              ." AND gp.idgroup= :idgroup";
        $sth_app = $this->db->prepare($sql);
        $sth_app->execute(array('idgroup' => $idgroup, 'idproduttore' => $idp));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }
    
    function getProduttoriByIdGroup($idgroup) {
        $sql = "SELECT * FROM produttori AS p"
              ." LEFT JOIN groups_produttori AS gp ON p.idproduttore=gp.idproduttore"
              ." WHERE gp.idgroup= :idgroup";
        $sth_app = $this->db->prepare($sql);
        $sth_app->execute(array('idgroup' => $idgroup));
        return $sth_app->fetchAll(PDO::FETCH_OBJ);        
    }

}
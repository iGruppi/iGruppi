<?php

/**
 * Description of Model_Prodotti
 * 
 * @author gullo
 */
class Model_Prodotti extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }


    function getProdottoById($idprodotto) {

        $sth_app = $this->db->prepare("SELECT * FROM prodotti WHERE idprodotto= :idprodotto");
        $sth_app->execute(array('idprodotto' => $idprodotto));
        return $sth_app->fetch(PDO::FETCH_ASSOC);
    }

    function getProdottiByIdProduttore($idproduttore, $attivo=null) {
        $sql = "SELECT p.*, c.descrizione AS categoria "
              ." FROM prodotti AS p"
              ." LEFT JOIN categorie AS c ON p.idcat=c.idcat"
              ." WHERE p.idproduttore= :idproduttore";
        $sql .= (!is_null($attivo)) ? " AND p.attivo='$attivo'" : "";
        $sql .= " ORDER BY p.descrizione";
        //echo $sql; die;
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore));        
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }


}
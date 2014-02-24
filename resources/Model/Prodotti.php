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


    function getProdottoById($idprodotto) 
    {
        $sth_app = $this->db->prepare("SELECT * FROM prodotti WHERE idprodotto= :idprodotto");
        $sth_app->execute(array('idprodotto' => $idprodotto));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }
    
    function getProdottoByCodice($codice) 
    {
        $sth_app = $this->db->prepare("SELECT * FROM prodotti WHERE codice= :codice");
        $sth_app->execute(array('codice' => $codice));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }

    function getProdottiByIdProduttore($idproduttore, $attivo=null) {
        $sql = "SELECT p.*, cs.descrizione AS categoria_sub, c.idcat, c.descrizione AS categoria "
              ." FROM prodotti AS p"
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat"
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE p.idproduttore= :idproduttore";
        $sql .= (!is_null($attivo)) ? " AND p.attivo='$attivo'" : "";
        $sql .=" ORDER BY c.descrizione, p.codice";
        //echo $sql; die;
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore));        
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getProdottiByIdSubCat($idsubcat) {
        
        $sth_app = $this->db->prepare("SELECT * FROM prodotti WHERE idsubcat= :idsubcat");
        $sth_app->execute(array('idsubcat' => $idsubcat));
        return $sth_app->fetchAll(PDO::FETCH_OBJ);
    }

}
<?php

/**
 * Description of Model_Cateogorie
 * 
 * @author gullo
 */
class Model_Categorie extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }


    function getCategoriaById($idcat) {

        $sth_app = $this->db->prepare("SELECT * FROM categorie WHERE $idcat= :$idcat");
        $sth_app->execute(array('$idcat' => $idcat));
        return $sth_app->fetch(PDO::FETCH_ASSOC);
    }

    function getCategorie() {
        $sth = $this->db->query("SELECT * FROM categorie ORDER BY descrizione");
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getSubCategories($idgroup, $idproduttore) {
        $sql = "SELECT cs.*, c.descrizione AS cat_descrizione "
              ."FROM categorie_sub AS cs "
              ."LEFT JOIN categorie AS c ON cs.idcat=c.idcat "
              ."LEFT JOIN groups_produttori AS gp ON cs.idgroup=gp.idgroup AND cs.idproduttore=gp.idproduttore "
              ."WHERE gp.idgroup= :idgroup AND gp.idproduttore= :idproduttore";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup, 'idproduttore' => $idproduttore));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    function addSubCategoria($arVal) {
        $sth = $this->db->prepare("INSERT INTO categorie_sub SET idgroup= :idgroup, idproduttore= :idproduttore, idcat= :idcat, descrizione= :descrizione");
        if( $sth->execute($arVal)) {
            return $this->db->lastInsertId();
        } else {
            return null;
        }
    }
    
    function editSubCategorie($arVal) {
        $this->db->beginTransaction();
        $sth_update = $this->db->prepare("UPDATE categorie_sub SET idcat= :idcat, descrizione= :descrizione WHERE idsubcat= :idsubcat");
        foreach($arVal AS $arSubCat) {
            $sth_update->execute($arSubCat);
        }
        $this->db->commit();
    }

}
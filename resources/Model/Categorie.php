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
        $sql = "SELECT cs.* FROM categorie_sub AS cs "
              ."LEFT JOIN groups_produttori AS gp ON cs.idgroup=gp.idgroup AND cs.idproduttore=gp.idproduttore "
              ."WHERE gp.idgroup= :idgroup AND gp.idproduttore= :idproduttore";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup, 'idproduttore' => $idproduttore));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    function addSubCategorieToProduttore($idgroup, $idproduttore, $arVal) {
        $this->db->beginTransaction();
        
        // get All SubCat for this Produttore
        $subCat = array();
        $sql = "SELECT idsubcat FROM categorie_sub WHERE idgroup= ".$this->db->quote($idgroup)." AND idproduttore= ".$this->db->quote($idproduttore);
        foreach ($this->db->query($sql) as $row) {
            $subCat[] = $row["idsubcat"];
        }
        // prepare SQL UPDATE
        $sth_update = $this->db->prepare("UPDATE categorie_sub SET descrizione= :descrizione WHERE idsubcat= :idsubcat");
        // prepare SQL INSERT
        $sth_insert = $this->db->prepare("INSERT INTO categorie_sub SET idgroup= :idgroup, idproduttore= :idproduttore, idcat= :idcat, descrizione= :descrizione");

        foreach($arVal AS $catVal) {
            // check if EXISTS
            if(isset($catVal["idsubcat"]) && in_array($catVal["idsubcat"], $subCat)) {
                unset($catVal["idcat"]);
                $sth_update->execute($catVal);
            } else {
                // prepare Array values
                $catVal["idgroup"] = $idgroup;
                $catVal["idproduttore"] = $idproduttore;
                $sth_insert->execute($catVal);
            }
        }
        
        $this->db->commit();
    }

}
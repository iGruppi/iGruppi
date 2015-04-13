<?php

/**
 * Description of Model_Cateogorie
 * 
 * @author gullo
 */
class Model_Db_Categorie extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }


    function getCategoriaById($idcat) {

        $sth_app = $this->db->prepare("SELECT * FROM categorie WHERE idcat= :idcat");
        $sth_app->execute(array('idcat' => $idcat));
        return $sth_app->fetch(PDO::FETCH_ASSOC);
    }

    function getCategorie() {
        $sth = $this->db->query("SELECT * FROM categorie ORDER BY descrizione");
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getSubCategoriesByIdproduttore($idproduttore) {
        $sql = "SELECT cs.*, c.descrizione AS cat_descrizione "
              ."FROM categorie_sub AS cs "
              ."LEFT JOIN categorie AS c ON cs.idcat=c.idcat "
              ."WHERE cs.idproduttore= :idproduttore";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    function getCategories_withKeyIdProduttore() {
        $sql = "SELECT cs.*, c.descrizione AS cat_descrizione "
              ."FROM categorie_sub AS cs "
              ."LEFT JOIN categorie AS c ON cs.idcat=c.idcat ";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        $subCat = $sth->fetchAll(PDO::FETCH_OBJ);
        $res = array();
        if(count($subCat) > 0) {
            foreach($subCat AS $sub) {
                $res[$sub->idproduttore][$sub->idcat] = $sub->cat_descrizione;
            }
        }
        return $res;
    }

    function getCategoriesByIdOrdine($idordine)
    {
        $sql = "SELECT DISTINCT c.idcat, c.descrizione AS categoria, p.idproduttore, prod.ragsoc AS ragsoc_produttore "
                . " FROM ordini_prodotti AS op "
                . " JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
                . " JOIN produttori AS prod ON p.idproduttore=prod.idproduttore "
                . " LEFT JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
                . " LEFT JOIN categorie AS c ON cs.idcat=c.idcat "
                . " WHERE op.idordine= :idordine";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getCategoriesByIdListino($idlistino)
    {
        $sql = "SELECT DISTINCT c.idcat, c.descrizione AS categoria "
                . " FROM listini_prodotti AS lp "
                . " LEFT JOIN prodotti AS p ON lp.idprodotto=p.idprodotto "
                . " LEFT JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
                . " LEFT JOIN categorie AS c ON cs.idcat=c.idcat "
                . " WHERE lp.idlistino= :idlistino";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idlistino' => $idlistino));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }    

    
    function addSubCategoria($arVal) {
        $sth = $this->db->prepare("INSERT INTO categorie_sub SET idproduttore= :idproduttore, idcat= :idcat, descrizione= :descrizione");
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
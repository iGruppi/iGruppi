<?php

/**
 * Description of Model_Ordini
 * 
 * @author gullo
 */
class Model_Ordini extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    function getByIdOrdine($idordine) {
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN groups_produttori AS gp ON o.idgroup=gp.idgroup AND o.idproduttore=gp.idproduttore"
              ." WHERE o.idordine= :idordine";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        if($sth->rowCount() > 0) {
            return $sth->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getAllByIdProduttore($idproduttore, $idgroup, $iduser_ref) {
        
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN groups_produttori AS gp ON o.idgroup=gp.idgroup AND o.idproduttore=gp.idproduttore"
              ." WHERE gp.idproduttore= :idproduttore"
              ." AND gp.iduser_ref= :iduser_ref"
              ." AND gp.idgroup= :idgroup"
              ." ORDER BY o.archiviato, o.data_fine DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore, 'idgroup' => $idgroup, 'iduser_ref' => $iduser_ref));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getAllByIdgroupWithFilter($idgroup, array $filters = null) {
        
        $arFilters = array('idgroup' => $idgroup);
        
        $sql = "SELECT o.*, p.*, u.nome, u.cognome "
             ." FROM ordini AS o "
             ." LEFT JOIN groups_produttori AS gp ON o.idgroup=gp.idgroup AND o.idproduttore=gp.idproduttore "
             ." LEFT JOIN produttori AS p ON gp.idproduttore=p.idproduttore "
             ." LEFT JOIN users AS u ON gp.iduser_ref=u.iduser "
             ." WHERE gp.idgroup= :idgroup";
        if(is_array($filters) && count($filters) > 0) {
            foreach($filters AS $fField => $fValue) {
                switch ($fField) {
                    case "idproduttore":
                        $sql .= " AND gp.idproduttore= :idproduttore";
                        $arFilters["idproduttore"] = $fValue;
                        break;

                    case "stato":
                        $sql .= Model_Ordini_Status::getSqlFilterByStato($fValue);
                        break;
/*                    
                    case "periodo":
                        $sql .= " AND DATE_FORMAT(o.data_inizio, '%Y%m') = :periodo";
                            $arFilters["periodo"] = $fValue;
                        break;
 * 
 */
                }
            }
        }
        $sql .= " ORDER BY o.archiviato, o.data_fine DESC";
        $sth = $this->db->prepare($sql);
//        Zend_Debug::dump($sth);die;
        $sth->execute($arFilters);
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getAllByDate($date, $type) {
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN groups AS g ON o.idgroup=g.idgroup"
              ." LEFT JOIN produttori AS p ON o.idproduttore=p.idproduttore"
              ." WHERE DATE_FORMAT(o.$type, '%Y-%m-%d')= :date";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('date' => $date));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    
/****************************************************************************************
 *  QUERY x PRODOTTI, CALCOLI e DETTAGLI ORDINE
 * 
 */    
    
    function getProdottiByIdOrdine($idordine) {
        // get elenco prodotti disponibile per quest'ordine
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, op.disponibile, cs.descrizione AS categoria_sub, c.idcat, c.descrizione AS categoria "
              ." FROM ordini_prodotti AS op "
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE op.idordine= :idordine"
              ." ORDER BY c.descrizione, p.codice";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine));        
        //Zend_Debug::dump($sthp->fetchAll(PDO::FETCH_OBJ));die;
        return $sthp->fetchAll(PDO::FETCH_OBJ);
    }    
    
    function addProdottiToOrdine($idordine, $arVal) {
        $this->db->beginTransaction();
        // prepare SQL INSERT
        $sth_insert = $this->db->prepare("INSERT INTO ordini_prodotti SET idprodotto= :idprodotto, idordine= :idordine, costo= :costo");
        foreach($arVal AS &$prodVal) {
            $prodVal["idordine"] = $idordine;
            $res = $sth_insert->execute($prodVal);
            if(!$res) {
                $this->db->rollBack();
                return false;
            }
        }
        return $this->db->commit();
    }
    
    function updateProdottiForOrdine($idordine, $arVal) {
        $this->db->beginTransaction();
        // prepare SQL UPDATE
        $sth_update = $this->db->prepare("UPDATE ordini_prodotti SET costo= :costo, disponibile= :disponibile WHERE idprodotto= :idprodotto AND idordine= :idordine");
        foreach($arVal AS &$prodVal) {
            $prodVal["idordine"] = $idordine;
            $res = $sth_update->execute($prodVal);
            if(!$res) {
                $this->db->rollBack();
                return false;
            }
        }
        return $this->db->commit();
    }
    
    function setQtaProdottiForOrdine($idordine, $iduser, $arQta) {
        $this->db->beginTransaction();
        if(count($arQta) > 0) {
            // delete all records in ordini_user_prodotti
            $resd = $this->db->query("DELETE FROM ordini_user_prodotti WHERE iduser='$iduser' AND idordine='$idordine'");
            if(!$resd) {
                $this->db->rollBack();
                return false;
            }
            // prepare SQL INSERT
            $sth = $this->db->prepare("INSERT INTO ordini_user_prodotti SET iduser= :iduser, idprodotto= :idprodotto, idordine= :idordine, qta= :qta, qta_reale= :qta, data_ins=NOW()");
            foreach ($arQta as $idprodotto => $qta) {
                if( $qta > 0) {
                    // insert product selected
                    $fields = array('iduser' => $iduser, 'idprodotto' => $idprodotto, 'idordine' => $idordine, 'qta' => $qta);
                    $res = $sth->execute($fields);
                    if(!$res) {
                        $this->db->rollBack();
                        return false;
                    }
                }
            }
        }
        return $this->db->commit();
    }
    
    
    function getProdottiOrdinatiByIdordine($idordine) {
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, op.disponibile, "
              ." cs.descrizione AS categoria_sub, cs.idsubcat, c.descrizione AS categoria, c.idcat, "
              ." u.nome, u.cognome, u.email, oup.qta, oup.qta_reale, oup.iduser "
              ." FROM ordini_prodotti AS op"
              ." LEFT OUTER JOIN ordini_user_prodotti AS oup ON op.idprodotto=oup.idprodotto AND op.idordine=oup.idordine"
              ." LEFT OUTER JOIN users AS u ON oup.iduser=u.iduser"
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE op.idordine= :idordine"
//              ." GROUP BY u.iduser, oup.idprodotto"
              ." ORDER BY p.codice";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine));
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        return $prodotti;
    }
    
    
}
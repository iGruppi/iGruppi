<?php

/**
 * Description of Model_Ordini
 * 
 * @author gullo
 */
class Model_Db_Ordini extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    function getByIdOrdine($idordine) {
        $sql = "SELECT * FROM ordini WHERE idordine= :idordine";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        if($sth->rowCount() > 0) {
            return $sth->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getAllByIdUserRef($iduser_ref) {
        
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN ordini_groups AS og ON o.idordine=og.idordine"
              ." WHERE og.iduser_ref= :iduser_ref"
              ." ORDER BY o.archiviato, o.data_fine DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser_ref' => $iduser_ref));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getAllByIdgroupWithFilter($idgroup, array $filters = null) {
        
        $arFilters = array('idgroup' => $idgroup);
        
        $sql = "SELECT o.*, og.*, u.nome AS ref_nome, u.cognome AS ref_cognome "
             ." FROM ordini AS o "
             ." JOIN ordini_groups AS og ON og.idordine=o.idordine AND og.idgroup_slave= :idgroup"
             ." LEFT JOIN users AS u ON og.iduser_ref=u.iduser "
//             ." LEFT JOIN referenti AS r ON o.idgroup=r.idgroup AND o.idproduttore=r.idproduttore "
//             ." LEFT JOIN produttori AS p ON r.idproduttore=p.idproduttore "
             ." WHERE 1";
        if(is_array($filters) && count($filters) > 0) {
            foreach($filters AS $fField => $fValue) {
                switch ($fField) {
/*
                    case "idproduttore":
                        $sql .= " AND r.idproduttore= :idproduttore";
                        $arFilters["idproduttore"] = $fValue;
                        break;
*/
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
            $sth = $this->db->prepare("INSERT INTO ordini_user_prodotti "
                    ."SET iduser= :iduser, idprodotto= :idprodotto, idordine= :idordine, qta= :qta, "
                    ."qta_reale= ((SELECT moltiplicatore FROM prodotti WHERE idprodotto= :idprodotto) * :qta), data_ins=NOW()");
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
    
    function addQtaProdottoForOrdine($idordine, $iduser, $idprodotto) 
    {
        $sth = $this->db->prepare("SELECT * FROM ordini_user_prodotti WHERE iduser= :iduser AND idordine= :idordine AND idprodotto= :idprodotto");
        $sth->execute(array('idordine' => $idordine, 'iduser' => $iduser, 'idprodotto' => $idprodotto));
        if($sth->rowCount() == 0) {
            // prepare SQL INSERT
            $sthi = $this->db->prepare("INSERT INTO ordini_user_prodotti "
                    ."SET iduser= :iduser, idprodotto= :idprodotto, idordine= :idordine, qta= :qta, "
                    ."qta_reale= ((SELECT moltiplicatore FROM prodotti WHERE idprodotto= :idprodotto) * :qta), data_ins=NOW()");
            // insert product selected
            $fields = array('iduser' => $iduser, 'idprodotto' => $idprodotto, 'idordine' => $idordine, 'qta' => 1);
            $res = $sthi->execute($fields);
            if($res) {
                return true;
            }
        }
        return false;
    }
    
    function getProdottiOrdinatiByIdordine($idordine, $iduser=false, $idprodotto=false) 
    {
        // init array to execute
        $arExecute = array('idordine' => $idordine);
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, op.disponibile, "
              ." cs.descrizione AS categoria_sub, cs.idsubcat, c.descrizione AS categoria, c.idcat, "
              ." u.nome, u.cognome, u.email, oup.qta, oup.qta_reale, oup.iduser "
              ." FROM ordini_prodotti AS op"
              ." LEFT OUTER JOIN ordini_user_prodotti AS oup ON op.idprodotto=oup.idprodotto AND op.idordine=oup.idordine"
              ." LEFT OUTER JOIN users AS u ON oup.iduser=u.iduser"
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE op.idordine= :idordine";
        if($iduser !== false)
        {
            $sqlp .= " AND oup.iduser= :iduser";
            $arExecute["iduser"] = $iduser;
        }
        if($idprodotto !== false)
        {
            $sqlp .= " AND oup.idprodotto= :idprodotto";
            $arExecute["idprodotto"] = $idprodotto;
        }
        $sqlp .= " ORDER BY p.codice";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute($arExecute);
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        return $prodotti;
    }
    
    
}
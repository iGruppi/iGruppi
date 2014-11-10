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
                        $sql .= $this->getSqlFilterByStato($fValue);
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
//        echo $sql; die;
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
    
    
    function getGroupsByIdOrdine($idordine)
    {
        $sql = "SELECT og.*, og.idordine AS id, g_slave.nome AS group_nome, u_slave.iduser AS ref_iduser, u_slave.nome AS ref_nome, u_slave.cognome AS ref_cognome "
                . " FROM ordini_groups AS og "
            // JOIN SLAVES
                . " JOIN groups AS g_slave ON og.idgroup_slave=g_slave.idgroup "
                . " LEFT JOIN referenti AS ref_slave ON g_slave.idgroup=ref_slave.idgroup "
                . " LEFT JOIN users AS u_slave ON ref_slave.iduser_ref=u_slave.iduser "
                . " WHERE og.idordine= :idordine "
                . " GROUP BY og.idgroup_master, og.idgroup_slave";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    
/****************************************************************************************
 *  QUERY x PRODOTTI, CALCOLI e DETTAGLI ORDINE
 * 
 */    
    
    function addProdottiToOrdine($idordine, $arVal) {
        $this->db->beginTransaction();
        // prepare SQL INSERT
        $sth_insert = $this->db->prepare("INSERT INTO ordini_prodotti SET idprodotto= :idprodotto, idordine= :idordine, costo_ordine= :costo_ordine");
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
    
    function updateProdottiForOrdine(Model_Ordini_Ordine $ordine, array $arVal) {
        $this->db->beginTransaction();
        // prepare SQL UPDATE
        $sth_update = $this->db->prepare("UPDATE ordini_prodotti SET costo_ordine= :costo_ordine, disponibile_ordine= :disponibile_ordine WHERE idordine= :idordine AND idlistino= :idlistino AND idprodotto= :idprodotto");
        foreach($arVal AS &$prodVal) {
            $prodVal["idordine"] = $ordine->getIdOrdine();
            $prodVal["idlistino"] = $ordine->getIdListino();
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
    
    function getProdottiOrdinatiByIdordine($idordine) 
    {
        $sqlp = "SELECT * FROM ordini_user_prodotti WHERE idordine= :idordine";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine));
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        return $prodotti;
    }
    
    
    
    /**
     *  SQL FILTERS for STATO
     *  Logica per filtrare lo Stato di un ordine
     */
    private function getSqlFilterByStato($stato) {
        switch ($stato)
        {
            case Model_Ordini_State_States_Pianificato::STATUS_NAME:
                $sql = " AND NOW() < o.data_inizio AND o.archiviato='N'";
                break;

            case Model_Ordini_State_States_Aperto::STATUS_NAME:
                $sql = " AND NOW() >= o.data_inizio AND NOW() <= o.data_fine AND o.archiviato='N'";
                break;
            
            case Model_Ordini_State_States_Chiuso::STATUS_NAME:
                $sql = " AND NOW() > o.data_fine AND ( NOW() <= o.data_inviato OR o.data_inviato IS NULL) AND o.archiviato='N'";
                break;

            case Model_Ordini_State_States_Inviato::STATUS_NAME:
                $sql = " AND NOW() > o.data_inviato AND ( NOW() <= o.data_arrivato OR o.data_arrivato IS NULL) AND o.archiviato='N'";
                break;

            case Model_Ordini_State_States_Arrivato::STATUS_NAME:
                $sql = " AND NOW() > o.data_arrivato AND ( NOW() <= o.data_consegnato OR o.data_consegnato IS NULL) AND o.archiviato='N'";
                break;

            case Model_Ordini_State_States_Consegnato::STATUS_NAME:
                $sql = " AND NOW() > o.data_consegnato AND o.archiviato='N'";
                break;

            case Model_Ordini_State_States_Archiviato::STATUS_NAME:
                $sql = " AND o.archiviato='S' ";
                break;

        }
        return $sql; 
    }
    
    
}
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
        $sql = "SELECT o.*, CONCAT(u.nome, ' ', u.cognome) AS supervisore_name,"
            . " g.idgroup AS supervisore_idgroup, g.nome AS supervisore_group"
            . " FROM ordini AS o"
            . " JOIN users AS u ON o.iduser_ref=u.iduser"
            . " JOIN users_group AS ug ON o.iduser_ref=ug.iduser"
            . " JOIN groups AS g ON ug.idgroup=g.idgroup"
            . " WHERE idordine= :idordine";
        
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        if($sth->rowCount() > 0) {
            return $sth->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getOrdiniByIdIdgroup($idgroup, $condivisione = null) {
        
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN ordini_groups AS og ON o.idordine=og.idordine"
              ." WHERE 1 ";
        $params = array();
        if(!is_null($condivisione))
        {
            switch ($condivisione) {
                case "PUB":
                    $sql .= " AND o.condivisione='PUB' ";
                    break;
                case "SHA":
                    $sql .= " AND o.condivisione='SHA' AND og.idgroup_slave= :idgroup";
                    $params = array('idgroup' => $idgroup);
                    break;
                default:
                case "PRI":
                    $sql .= " AND o.condivisione='PRI' AND og.idgroup_slave= :idgroup";
                    $params = array('idgroup' => $idgroup);
                    break;
            }
        }

        $sql .= " ORDER BY o.archiviato, o.data_fine DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute($params);
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
    
    
    function getOrdiniToClose($idgroup)
    {
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN ordini_groups AS og ON o.idordine=og.idordine"
              ." WHERE o.data_consegnato IS NOT NULL "
              ." AND ("
                . " o.condivisione='PUB' OR og.idgroup_slave= :idgroup"
                . ")"
              ." ORDER BY o.archiviato, o.data_consegnato DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;

    }
    
    function getGroupsByIdOrdine($idordine)
    {
        $sql = "SELECT og.*, og.idordine AS id, g_slave.nome AS group_nome, u_slave.iduser AS ref_iduser, u_slave.nome AS ref_nome, u_slave.cognome AS ref_cognome "
                . " FROM ordini_groups AS og "
            // JOIN SLAVES and related iduser_ref
                . " JOIN groups AS g_slave ON og.idgroup_slave=g_slave.idgroup "
                . " LEFT JOIN users AS u_slave ON og.iduser_ref=u_slave.iduser "
                . " WHERE og.idordine= :idordine "
                . " GROUP BY og.idgroup_master, og.idgroup_slave";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    
    /**
     * Insert all products of $listini array in the new Ordine
     * @param int $idordine
     * @param array $listini
     */
    public function createOrdiniByListini($idordine, array $listini)
    {
        if(count($listini) > 0)
        {
            $this->db->beginTransaction();
            foreach ($listini AS $idlistino)
            {
                $sth = $this->db->prepare("INSERT INTO ordini_prodotti (idordine, idlistino, idprodotto, costo_ordine) "
                                         ."    SELECT :idordine, idlistino, idprodotto, costo_listino "
                                         . "   FROM listini_prodotti "
                                         ."    WHERE idlistino= :idlistino AND attivo_listino='S'");
                $res = $sth->execute(array('idordine' => $idordine, 'idlistino' => $idlistino));
                if(!$res) {
                    $this->db->rollBack();
                    return false;
                }
            }
            return $this->db->commit();
        }
        return false;
    }
    

    

    /**
     * SET QTA and QTA_REALE for ORDINE by iduser
     * @param int $idordine
     * @param int $idlistino
     * @param int $idprodotto
     * @param int $iduser
     * @param int $qta
     * @return boolean return true if the queries does not return errors
     */
    function setQtaProdottoForOrdine($idordine, $idlistino, $idprodotto, $iduser, $qta) 
    {
        // Check for record in ordini_users
        $ordini_users_fields = array('idordine' => $idordine, 'iduser' => $iduser);
        $sthou = $this->db->prepare("SELECT * FROM ordini_users WHERE idordine= :idordine AND iduser= :iduser");
        $sthou->execute($ordini_users_fields);
        $resou = true;
        if($sthou->rowCount() == 0) {
            $sthou_insert = $this->db->prepare("INSERT INTO ordini_users SET idordine= :idordine, iduser= :iduser");
            $resou = $sthou_insert->execute($ordini_users_fields);
        }
        // Prepare to insert products
        if($resou) 
        {
            $this->db->beginTransaction();
            // delete all records in ordini_user_prodotti
            $resd = $this->db->query("DELETE FROM ordini_user_prodotti WHERE iduser='$iduser' AND idordine='$idordine' AND idprodotto= '$idprodotto' AND idlistino = '$idlistino'");
            if(!$resd) {
                $this->db->rollBack();
                return false;
            }
            if($qta > 0) {
                // prepare SQL INSERT
                $sth = $this->db->prepare("INSERT INTO ordini_user_prodotti "
                        ."SET iduser= :iduser, idprodotto= :idprodotto, idlistino = :idlistino, idordine= :idordine, qta= :qta, "
                        ."qta_reale= ((SELECT moltiplicatore FROM prodotti WHERE idprodotto= :idprodotto) * :qta), data_ins=NOW()");
                $fields = array('iduser' => $iduser, 'idprodotto' => $idprodotto, 'idlistino' => $idlistino, 'idordine' => $idordine, 'qta' => $qta);
                $res = $sth->execute($fields);
                if(!$res) {
                    $this->db->rollBack();
                    return false;
                }
            }
            return $this->db->commit();
        }
        return false;
    }
    
    
    /**
     * ADD QTA (and set QTA_REALE) for ORDINE by iduser
     * @param int $idordine
     * @param int $iduser
     * @param int $idprodotto
     * @return boolean return true if the queries does not get errors
     */
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
    
    
    /**
     * GET all prodotti ordered by idordine
     * @param int $idordine
     * @return array the results set
     */
    function getProdottiOrdinatiByIdordineAndIdgroup($idordine, $idgroup) 
    {
        $sqlp = "SELECT oup.* "
               ." FROM ordini_user_prodotti AS oup "
               ." JOIN users_group AS up ON oup.iduser=up.iduser"
               ." WHERE oup.idordine= :idordine"
               ." AND up.idgroup = :idgroup";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine, 'idgroup' => $idgroup));
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
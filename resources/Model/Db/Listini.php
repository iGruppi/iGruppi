<?php

/**
 * Description of Model_Db_Listini
 * 
 * @author gullo
 */
class Model_Db_Listini extends MyFw_DB_Base {
    
    function __construct() {
        parent::__construct();
    }

    function getListinoById($idlistino)
    {
        $sth = $this->db->prepare("SELECT l.*, p.ragsoc "
                . " FROM listini AS l "
                . " LEFT JOIN produttori AS p ON l.idproduttore=p.idproduttore "
                . " WHERE l.idlistino= :idlistino");
        $sth->execute(array('idlistino' => $idlistino));
        return $sth->fetch(PDO::FETCH_OBJ);
    }
    
    function getListiniByIdgroup($idgroup, $condivisione = null)
    {
        $sql = "SELECT l.*, p.ragsoc, u.iduser, u.nome, u.cognome, u.email "
                . " FROM listini AS l "
                . " JOIN produttori AS p ON l.idproduttore=p.idproduttore "
                . " JOIN listini_groups AS lg ON l.idlistino=lg.idlistino "
                . " JOIN referenti AS ref ON ref.idproduttore=l.idproduttore AND ref.idgroup= lg.idgroup_master"
                . " JOIN users AS u ON ref.iduser_ref=u.iduser"
                . " WHERE 1 ";
        $params = array();
        if(!is_null($condivisione))
        {
            switch ($condivisione) {
                case "PUB":
                    $sql .= " AND l.condivisione='PUB' ";
                    break;
                case "SHA":
                    $sql .= " AND l.condivisione='SHA' AND lg.idgroup_slave= :idgroup";
                    $params = array('idgroup' => $idgroup);
                    break;
                default:
                case "PRI":
                    $sql .= " AND l.condivisione='PRI' AND lg.idgroup_slave= :idgroup";
                    $params = array('idgroup' => $idgroup);
                    break;
            }
        }
        $sth = $this->db->prepare($sql);
        $sth->execute($params);
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    function getGroupsByIdlistino($idlistino)
    {
        $sql = "SELECT gl.*, gl.idlistino AS id, g_slave.nome AS group_nome, u_slave.iduser AS ref_iduser, u_slave.nome AS ref_nome, u_slave.cognome AS ref_cognome "
                . " FROM listini_groups AS gl "
            // JOIN SLAVES and REFERENTI
                . " JOIN groups AS g_slave ON gl.idgroup_slave=g_slave.idgroup "
                . " LEFT JOIN referenti AS ref_slave ON g_slave.idgroup=ref_slave.idgroup "
                . " LEFT JOIN users AS u_slave ON ref_slave.iduser_ref=u_slave.iduser "
                . " WHERE gl.idlistino= :idlistino "
                . " GROUP BY gl.idgroup_master, gl.idgroup_slave";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idlistino' => $idlistino));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    function getListiniAvailableToCreateOrder($idgroup, $iduser)
    {
        $sql = "SELECT l.* , p.ragsoc
                FROM listini_groups AS lg
                JOIN listini AS l ON lg.idlistino = l.idlistino
                JOIN produttori AS p ON l.idproduttore=p.idproduttore
                LEFT JOIN referenti AS ref ON l.idproduttore = ref.idproduttore AND lg.idgroup_slave = ref.idgroup
                JOIN users AS u ON ref.iduser_ref=u.iduser
                WHERE ref.iduser_ref = :iduser AND 
                (
                    l.condivisione =  'PUB'
                OR ( lg.idgroup_slave = :idgroup AND lg.visibile =  'S' )
                )";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup, 'iduser' => $iduser));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
}

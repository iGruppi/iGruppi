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
    
    function getListiniByIdgroup($idgroup)
    {
        $sql = "SELECT l.*, "
                . " p.ragsoc, u.iduser, u.nome, u.cognome, u.email "
                . " FROM listini AS l "
                . " JOIN listini_groups AS lg ON l.idlistino=lg.idlistino"
                . " JOIN produttori AS p ON l.idproduttore=p.idproduttore "
                . " JOIN referenti AS ref ON ref.idproduttore=l.idproduttore AND ref.idgroup= lg.idgroup_master"
                . " JOIN users AS u ON ref.iduser_referente=u.iduser"
                . " WHERE l.condivisione='PUB'"
                . " OR (l.condivisione='PRI' AND lg.idgroup_master= :idgroup)"
                . " OR (l.condivisione='SHA' AND lg.idgroup_slave = :idgroup)";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    function getGroupsByIdlistino($idlistino)
    {
        $sql = "SELECT gl.*, gl.idlistino AS id, g_slave.nome AS group_nome, u_slave.iduser AS iduser_referente, u_slave.nome AS nome_referente, u_slave.cognome AS cognome_referente "
                . " FROM listini AS l"
                . " JOIN listini_groups AS gl ON l.idlistino=gl.idlistino"
            // JOIN SLAVES and REFERENTI
                . " JOIN groups AS g_slave ON gl.idgroup_slave=g_slave.idgroup "
                . " LEFT JOIN referenti AS ref_slave ON ref_slave.idproduttore=l.idproduttore AND g_slave.idgroup=ref_slave.idgroup "
                . " LEFT JOIN users AS u_slave ON ref_slave.iduser_referente=u_slave.iduser "
                . " WHERE l.idlistino= :idlistino ";
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
                JOIN referenti AS ref ON l.idproduttore = ref.idproduttore AND lg.idgroup_slave = :idgroup
                WHERE ref.iduser_referente = :iduser AND lg.visibile = 'S'";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup, 'iduser' => $iduser));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    
    function addProdottiToListinoByIdProduttore($idlistino, $idproduttore)
    {
        $sql = "INSERT INTO listini_prodotti (idlistino, idprodotto, descrizione_listino, costo_listino, note_listino)
                SELECT :idlistino, idprodotto, descrizione, costo, note FROM prodotti WHERE idproduttore= :idproduttore AND attivo='S' AND production='S'";
        $sth = $this->db->prepare($sql);
        return $sth->execute(array('idlistino' => $idlistino, 'idproduttore' => $idproduttore));
    }
    
    function addProdottoToListinoByIdProdotto($idlistino, $idprodotto)
    {
        $sql = "INSERT INTO listini_prodotti (idlistino, idprodotto, descrizione_listino, costo_listino, note_listino)
                SELECT :idlistino, idprodotto, descrizione, costo, note FROM prodotti WHERE idprodotto= :idprodotto";
        $sth = $this->db->prepare($sql);
        return $sth->execute(array('idlistino' => $idlistino, 'idprodotto' => $idprodotto));
    }
    
    
    function updateListinoProdotti($idlistino, $idprodotto, $field, $value)
    {
        $sql = "UPDATE listini_prodotti SET $field = :value WHERE idlistino= :idlistino AND idprodotto= :idprodotto";
        $sth = $this->db->prepare($sql);
        return $sth->execute(array('idlistino' => $idlistino, 'idprodotto' => $idprodotto, 'value' => $value));
    }
    
    function updateDataListino($idlistino)
    {
        $sql = "UPDATE listini SET user_update = NOW() WHERE idlistino= :idlistino";
        $sth = $this->db->prepare($sql);
        return $sth->execute(array('idlistino' => $idlistino));
    }
}

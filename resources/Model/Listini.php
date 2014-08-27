<?php

/**
 * Description of Model_Listini
 * 
 * @author gullo
 */
class Model_Listini extends MyFw_DB_Base {
    
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
        $sql = "SELECT l.*, gl.idgroup_slave, gl.idgroup_master, gl.valido_dal, gl.valido_al, p.ragsoc "
                . " FROM listini AS l "
                . " LEFT JOIN produttori AS p ON l.idproduttore=p.idproduttore "
                . " LEFT JOIN listini_groups AS gl ON l.idlistino=gl.idlistino "
                . " WHERE l.condivisione='PUB' " // Tutti i gruppi vedono i Listini pubblici
                . " OR gl.idgroup_slave= :idgroup";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    function getGroupsByIdlistino($idlistino)
    {
        $sql = "SELECT gl.*, g_slave.nome AS group_nome, u_slave.iduser AS ref_iduser, u_slave.nome AS ref_nome, u_slave.cognome AS ref_cognome "
                . " FROM listini_groups AS gl "
            // JOIN SLAVES
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
    
    function getCategoriesByIdlistino($idlistino)
    {
        $sql = "SELECT DISTINCT c.idcat, c.descrizione "
                . " FROM listini_prodotti AS lp "
                . " LEFT JOIN prodotti AS p ON lp.idprodotto=p.idprodotto "
                . " LEFT JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
                . " LEFT JOIN categorie AS c ON cs.idcat=c.idcat "
                . " WHERE lp.idlistino= :idlistino";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idlistino' => $idlistino));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
}

<?php
/**
 * Description of Model_Db_Notifiche
 * 
 * @author gullo
 */
class Model_Db_Notifiche extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }

    
    // ELENCO INDIRIZZI AMMINISTRATORI DEI GRUPPI COI QUALI E' STATO CONDIVISO L'ORDINE
    function getEmails_Admins($arGroupsIds)
    {
        $sql = "select u.email
                from users u
                join users_group ug
                on u.iduser=ug.iduser
                where ug.idgroup in (".implode(",", $arGroupsIds).")
                and fondatore='S'";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }

    // ELENCO INDIRIZZI INCARICATI DELL'ORDINE
    function getEmails_Incaricati($idordine, $arGroupsIds)
    {
        $sql = "select u.email 
                from users u
                join ordini_groups og
                on og.iduser_incaricato=u.iduser
                where idordine = :idordine
                and og.idgroup_slave in (".implode(",", $arGroupsIds).")";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }

    // ELENCO INDIRIZZI CHI HA ORDINATO
    function getEmails_UsersOrder($idordine, $arGroupsIds)
    {
        $sql = "select distinct u.email
                from users u
                join ordini_user_prodotti oup
                on u.iduser=oup.iduser
                join users_group ug
                on u.iduser=ug.iduser 
                where oup.idordine = :idordine
                and ug.idgroup in (".implode(",", $arGroupsIds).")";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
}

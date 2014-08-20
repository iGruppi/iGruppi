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

    function getListiniByIdgroup($idgroup)
    {
        $sql = "SELECT l.*, gl.idgroup_slave, gl.idgroup_master, gl.valido_dal, gl.valido_al "
                . " FROM groups_listini AS gl "
                . " RIGHT JOIN listini AS l ON gl.idlistino=l.idlistino "
                . " WHERE l.pubblico='S' " // Tutti i gruppi vedono i Listini pubblici
                . " OR gl.idgroup_slave= :idgroup";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;        
    }
    
    function getListinoById($idlistino)
    {
        $sth = $this->db->prepare("SELECT * FROM listini WHERE idlistino= :idlistino");
        $sth->execute(array('idlistino' => $idlistino));
        return $sth->fetch(PDO::FETCH_OBJ);
    }
    
    
}

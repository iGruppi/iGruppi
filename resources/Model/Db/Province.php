<?php

/**
 * Description of Model_Db_Province
 * 
 * @author gullo
 */
class Model_Db_Province extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }

    function getAll()
    {
        $sth = $this->db->prepare("SELECT * FROM province ORDER BY provdesc");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_CLASS);
    }
    
    function getProvinciaBySigla($provincia)
    {
        $sth_app = $this->db->prepare("SELECT * FROM province WHERE provincia= :provincia");
        $sth_app->execute(array('provincia' => $provincia));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }

}
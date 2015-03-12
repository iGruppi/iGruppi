<?php

/**
 * Description of Model_Cateogorie
 * 
 * @author gullo
 */
class Model_Db_Cassa extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }


    function getMovimentoById($idmovimento) {

        $sth_app = $this->db->prepare("SELECT * FROM cassa WHERE idmovimento= :idmovimento");
        $sth_app->execute(array('idmovimento' => $idmovimento));
        return $sth_app->fetch(PDO::FETCH_ASSOC);
    }

    function getUltimiMovimenti($start=0, $limit=20) {
        $sql = "SELECT c.*, u.nome, u.cognome, u.email,"
              ." o.data_inizio "
              ." FROM cassa AS c "
              ." JOIN users AS u ON c.iduser=u.iduser "
              ." LEFT JOIN ordini AS o ON c.idordine=o.idordine"
              ." ORDER BY c.data DESC"
              ." LIMIT $start, $limit";
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    function addMovimentoOrdine(array $movimento)
    {
        $sth = $this->db->prepare("INSERT INTO cassa SET iduser= :iduser, importo= :importo, data= :data, descrizione= :descrizione, idordine= :idordine");
        return $sth->execute($movimento);
    }
    
    function getMovimentiByIduser($iduser)
    {
        $sql = "SELECT c.*, o.data_inizio "
              ." FROM cassa AS c "
              ." LEFT JOIN ordini AS o ON c.idordine=o.idordine"
              ." WHERE iduser= :iduser"
              ." ORDER BY c.data DESC"
              ." LIMIT 0,10";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
}
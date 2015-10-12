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

    function getUltimiMovimentiByIdgroup($idgroup, $start=0, $limit=20) {
        $sql = "SELECT c.*, u.nome, u.cognome, u.email,"
              ." o.data_inizio "
              ." FROM cassa AS c "
              ." JOIN users AS u ON c.iduser=u.iduser "
              ." LEFT JOIN ordini AS o ON c.idordine=o.idordine"
              ." WHERE c.iduser IN (SELECT iduser FROM users_group WHERE idgroup= :idgroup AND attivo='S')"
              ." ORDER BY c.data DESC"
              ." LIMIT $start, $limit";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
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
    
    function getSaldiGroup($idgroup)
    {
        $sql = "SELECT concat( cognome, ' ', users.nome ) AS Utente, coalesce(TotAttivi, 0) as TotaleVersamenti, coalesce(TotPassivi, 0) as TotaleOrdiniPagati, 
                coalesce(Num_ordini, 0) as NumeroOrdiniArchiviati, coalesce(Saldo,0) as SaldoUtente, coalesce(Num_ordini_attivi,0) as NumeroOrdiniInCorso, 
                -1*coalesce(StimaProxSpese,0) as StimaSpeseProxOrdini, coalesce(Saldo,0)-coalesce(StimaProxSpese,0) as ProiezioneSaldo
                FROM users
                JOIN users_group ON users.iduser = users_group.iduser
                LEFT JOIN 
                (select iduser, coalesce( sum( case when idordine is null then importo else 0 end ) , 0 ) AS TotAttivi, coalesce( sum( case when idordine is not null then importo else 0 end ) , 0 ) AS TotPassivi, 
                coalesce( count( DISTINCT cassa.idordine ) , 0 ) AS Num_ordini, coalesce( sum( importo ) , 0 ) AS Saldo from cassa group by cassa.iduser) cassa1 
                ON cassa1.iduser = users.iduser
                LEFT JOIN 
                (select iduser, coalesce( count( DISTINCT ordini.idordine ) , 0 ) AS Num_ordini_attivi, ROUND(coalesce( sum( costo_ordine * qta_reale ) , 0 ),2) AS StimaProxSpese
                from ordini_user_prodotti 
                LEFT JOIN ordini_prodotti ON ordini_prodotti.idordine = ordini_user_prodotti.idordine and ordini_prodotti.idprodotto = ordini_user_prodotti.idprodotto
                LEFT JOIN ordini ON ordini_prodotti.idordine = ordini.idordine
                where ordini.archiviato = 'N'
                group by iduser) ordini_user_prodotti1
                ON ordini_user_prodotti1.iduser = users.iduser
                WHERE users_group.idgroup = :idgroup
                GROUP BY users.iduser
                ORDER BY cognome";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idgroup' => $idgroup));
        return $sth->fetchAll(PDO::FETCH_OBJ);        
    }
    
}
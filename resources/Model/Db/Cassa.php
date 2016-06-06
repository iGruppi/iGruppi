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
    
    function getMovimentiByIduser($iduser, $start=0, $limit=10)
    {
        $sql = "SELECT c.*, o.data_inizio "
              ." FROM cassa AS c "
              ." LEFT JOIN ordini AS o ON c.idordine=o.idordine"
              ." WHERE iduser= :iduser"
              ." ORDER BY c.data DESC"
              ." LIMIT $start, $limit";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser));
        return $sth->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    function getSaldiIduser($iduser, $idgroup)
    {
        $sql = "SELECT COALESCE(TotAttivi, 0) as TotaleVersamenti, 
                COALESCE(TotPassivi, 0) as TotaleOrdiniPagati, 
                COALESCE(Num_ordini, 0) as NumeroOrdiniArchiviati, 
                COALESCE(Saldo,0) as SaldoUtente, 
                COALESCE(Num_ordini_attivi,0) as NumeroOrdiniInCorso, 
                (-1*COALESCE(StimaProxSpese,0)) as StimaSpeseProxOrdini, 
                (COALESCE(Saldo,0)-COALESCE(StimaProxSpese,0)) as ProiezioneSaldo
                FROM users
                JOIN users_group ON users.iduser = users_group.iduser
                LEFT JOIN 
                    (SELECT iduser, 
                    COALESCE( sum( case when idordine is null then importo else 0 end ) , 0 ) AS TotAttivi, 
                    COALESCE( sum( case when idordine is not null then importo else 0 end ) , 0 ) AS TotPassivi, 
                    COALESCE( count( DISTINCT cassa.idordine ) , 0 ) AS Num_ordini, 
                    COALESCE( sum( importo ) , 0 ) AS Saldo 
                    FROM cassa 
                    WHERE cassa.iduser= :iduser
                    GROUP by cassa.iduser) cassa1 
                ON cassa1.iduser = users.iduser
                LEFT JOIN 
                    (SELECT iduser, 
                    COALESCE( count( DISTINCT ordini.idordine ) , 0 ) AS Num_ordini_attivi, 
                    ROUND(COALESCE( sum( costo_ordine * qta_reale ) , 0 ),2) AS StimaProxSpese
                    FROM ordini_user_prodotti 
                    LEFT JOIN ordini_prodotti ON ordini_prodotti.idordine = ordini_user_prodotti.idordine and ordini_prodotti.idprodotto = ordini_user_prodotti.idprodotto
                    LEFT JOIN ordini ON ordini_prodotti.idordine = ordini.idordine
                    LEFT JOIN ordini_groups ON ordini.idordine=ordini_groups.idordine AND ordini_groups.idgroup_slave= :idgroup
                    WHERE ordini_groups.archiviato = 'N'
                    AND ordini_prodotti.disponibile_ordine = 'S'
                    GROUP by iduser) ordini_user_prodotti1
                ON ordini_user_prodotti1.iduser = users.iduser
                WHERE users.iduser = :iduser
                GROUP BY users.iduser";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser, 'idgroup' => $idgroup));
        return $sth->fetch(PDO::FETCH_OBJ);
    }
    
    function getTotaleOrdiniInCorsoByIduser($iduser, $idgroup)
    {
        $sql = "SELECT o.*, og.*, u.nome AS nome_incaricato, u.cognome AS cognome_incaricato,
                ROUND(COALESCE( sum( costo_ordine * qta_reale ) , 0 ),2) AS TotOrdine
                FROM ordini_user_prodotti AS oup
                LEFT JOIN ordini_prodotti AS op ON op.idordine = oup.idordine and op.idprodotto = oup.idprodotto
                LEFT JOIN ordini AS o ON op.idordine = o.idordine
                LEFT JOIN ordini_groups AS og ON o.idordine=og.idordine AND og.idgroup_slave= :idgroup
                LEFT JOIN users AS u ON og.iduser_incaricato=u.iduser
                WHERE og.archiviato = 'N'
                AND op.disponibile_ordine = 'S'
                AND oup.iduser= :iduser
                GROUP by o.idordine";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('iduser' => $iduser, 'idgroup' => $idgroup));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    
    
    /**
     * Aggiunge un movimento di cassa
     * @param array $movimento
     * @return boolean
     */
    function addMovimentoOrdine(array $movimento)
    {
        $sth = $this->db->prepare("INSERT INTO cassa SET iduser= :iduser, importo= :importo, data= :data, descrizione= :descrizione, idordine= :idordine");
        return $sth->execute($movimento);
    }
    
    /**
     * Chiude ordine per gruppo
     * @param type $idordine
     * @param type $idgroup
     * @return boolean
     */
    function closeOrderByIdordineAndIdgroup($idordine, $idgroup)
    {
        $sth = $this->db->prepare("UPDATE ordini_groups SET archiviato='S' WHERE idordine= :idordine AND idgroup_slave= :idgroup ");
        return $sth->execute(array('idordine' => $idordine, 'idgroup' => $idgroup));
    }
    
    /**
     * CLOSE an ORDINE
     * 
     * @param Model_Ordini_CalcoliAbstract $ordine
     */
    function closeOrdine(Model_Ordini_CalcoliAbstract $ordine, $idgroup)
    {
        // Start a transaction...
        $this->db->beginTransaction();
        
        foreach ($ordine->getProdottiUtenti() AS $iduser => $user)
        {
            $produttoriList = ((count($ordine->getProduttoriList()) > 0) ? implode(", ", $ordine->getProduttoriList()) : "--");
            $importo = -1 * abs($ordine->getTotaleConExtraByIduser($iduser));
            $values = array(
                'iduser'    => $iduser,
                'importo'   => $importo,
                'data'      => date("Y-m-d H:i:s"),
                'descrizione' => 'Archiviato Ordine ' . $produttoriList,
                'idordine'  => $ordine->getIdOrdine()
            );
            $res = $this->addMovimentoOrdine($values);
            if(!$res) {
                $this->db->rollBack();
                return false;
            }
        }
        
        // CLOSE ORDINE per GRUPPO
        $res2 = $this->closeOrderByIdordineAndIdgroup($ordine->getIdOrdine(), $idgroup);
        if(!$res2) {
            $this->db->rollBack();
            return false;
        }
        
        return $this->db->commit();
    }
    
    
    function getSaldiGroup($idgroup)
    {
        $sql = "SELECT concat( cognome, ' ', users.nome ) AS Utente, COALESCE(TotAttivi, 0) as TotaleVersamenti, COALESCE(TotPassivi, 0) as TotaleOrdiniPagati, 
                COALESCE(Num_ordini, 0) as NumeroOrdiniArchiviati, COALESCE(Saldo,0) as SaldoUtente, COALESCE(Num_ordini_attivi,0) as NumeroOrdiniInCorso, 
                -1*COALESCE(StimaProxSpese,0) as StimaSpeseProxOrdini, COALESCE(Saldo,0)-COALESCE(StimaProxSpese,0) as ProiezioneSaldo
                FROM users
                JOIN users_group ON users.iduser = users_group.iduser
                LEFT JOIN 
                (select iduser, COALESCE( sum( case when idordine is null then importo else 0 end ) , 0 ) AS TotAttivi, COALESCE( sum( case when idordine is not null then importo else 0 end ) , 0 ) AS TotPassivi, 
                COALESCE( count( DISTINCT cassa.idordine ) , 0 ) AS Num_ordini, COALESCE( sum( importo ) , 0 ) AS Saldo from cassa group by cassa.iduser) cassa1 
                ON cassa1.iduser = users.iduser
                LEFT JOIN 
                (select iduser, COALESCE( count( DISTINCT ordini.idordine ) , 0 ) AS Num_ordini_attivi, ROUND(COALESCE( sum( costo_ordine * qta_reale ) , 0 ),2) AS StimaProxSpese
                from ordini_user_prodotti 
                LEFT JOIN ordini_prodotti ON ordini_prodotti.idordine = ordini_user_prodotti.idordine and ordini_prodotti.idprodotto = ordini_user_prodotti.idprodotto
                LEFT JOIN ordini ON ordini_prodotti.idordine = ordini.idordine
                LEFT JOIN ordini_groups ON ordini.idordine=ordini_groups.idordine AND ordini_groups.idgroup_slave= :idgroup
                where ordini_groups.archiviato = 'N'
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
<?php

/**
 * Description of Model_Ordini
 * 
 * @author gullo
 */
class Model_Ordini extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }
    
    function getByIdOrdine($idordine) {
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN groups_produttori AS gp ON o.idgp=gp.idgp"
              ." WHERE o.idordine= :idordine";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        if($sth->rowCount() > 0) {
            return $sth->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getAllByIdProduttore($idproduttore, $idgroup, $iduser_ref) {
        
        $sql = "SELECT * FROM ordini AS o"
              ." LEFT JOIN groups_produttori AS gp ON o.idgp=gp.idgp"
              ." WHERE gp.idproduttore= :idproduttore"
              ." AND gp.iduser_ref= :iduser_ref"
              ." AND gp.idgroup= :idgroup"
              ." ORDER BY o.archiviato, o.data_inizio";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore, 'idgroup' => $idgroup, 'iduser_ref' => $iduser_ref));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getProdottiByIdOrdine_Gestione($idordine, $idproduttore) {
        // get elenco completo prodotti
        $sqlp = "SELECT p.*, c.descrizione AS categoria FROM prodotti AS p LEFT JOIN categorie AS c ON p.idcat=c.idcat WHERE idproduttore= :idproduttore AND attivo='S'";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idproduttore' => $idproduttore));        
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        
        // get elenco prodotti ordinati
        $sql = "SELECT * FROM ordini_prodotti WHERE idordine= :idordine";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));        
        $prOrdered = $sth->fetchAll(PDO::FETCH_OBJ);
        
        // merge array
        if( count($prodotti) > 0 ) {
            foreach ($prodotti as $k1 => &$prodotto) {
                $prodotto->selected = false;
                foreach ($prOrdered as $k2 => $prO) {
                    if( $prodotto->idprodotto == $prO->idprodotto ) {
                        //echo "Selected: " . $prodotto->idprodotto . "<br>";
                        $prodotto->selected = true;
                        $prodotto->costo = $prO->costo;
                        $prodotto->sconto = $prO->sconto;
                        $prodotto->offerta = $prO->offerta;
                    }
                }
            }
        }
        //Zend_Debug::dump($prodotti);die;
        return $prodotti;
    }

    function getProdottiByIdOrdine($idordine, $idproduttore, $iduser) {
        // get elenco prodotti disponibile per quest'ordine
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, c.descrizione AS categoria "
              ." FROM ordini_prodotti AS op "
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie AS c ON p.idcat=c.idcat "
              ." WHERE op.idordine= :idordine";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine));        
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        
        // get elenco prodotti ordinati
        $sql = "SELECT * FROM ordini_user_prodotti WHERE idordine= :idordine AND iduser= :iduser";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine, 'iduser' => $iduser));        
        $prOrdered = $sth->fetchAll(PDO::FETCH_OBJ);
        
        // merge array
        if( count($prodotti) > 0 ) {
            foreach ($prodotti as $k1 => &$prodotto) {
                $prodotto->qta = 0;
                foreach ($prOrdered as $k2 => $prO) {
                    if( $prodotto->idprodotto == $prO->idprodotto ) {
                        $prodotto->qta = $prO->qta;
                        $prodotto->data_ins = $prO->data_ins;
                    }
                }
            }
        }
        //Zend_Debug::dump($prodotti);die;
        return $prodotti;
    }
    
    
    function getAllByIdgroup($idgroup, $archiviato=false) {
        
        $sql = "SELECT o.*, p.*, u.nome, u.cognome "
             ." FROM groups_produttori AS gp "
             ." LEFT JOIN ordini AS o ON gp.idgp=o.idgp "
             ." LEFT JOIN produttori AS p ON gp.idproduttore=p.idproduttore "
             ." LEFT JOIN users AS u ON gp.iduser_ref=u.iduser "
             ." WHERE gp.idgroup= :idgroup"
             ." AND o.archiviato= :archiviato";
        $sth = $this->db->prepare($sql);
        $arc = ($archiviato) ? "S" : "N";
        $sth->execute(array('idgroup' => $idgroup, 'archiviato' => $arc));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getProdottiOrdinatiByIdOrdine($idordine) {
        // get elenco prodotti disponibile per quest'ordine
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, c.descrizione AS categoria, "
              ." SUM(oup.qta) AS qta_ord, u.iduser, u.nome, u.cognome "
              ." FROM ordini_user_prodotti AS oup"
              ." JOIN users AS u ON oup.iduser=u.iduser"
              ." JOIN ordini_prodotti AS op ON oup.idprodotto=op.idprodotto AND oup.idordine=op.idordine"
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie AS c ON p.idcat=c.idcat "
              ." WHERE op.idordine= :idordine"
              ." GROUP BY oup.iduser, oup.idprodotto";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine));        
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        //Zend_Debug::dump($prodotti);die;
        return $prodotti;
    }
    
}
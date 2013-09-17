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
              ." LEFT JOIN groups_produttori AS gp ON o.idgroup=gp.idgroup AND o.idproduttore=gp.idproduttore"
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
              ." LEFT JOIN groups_produttori AS gp ON o.idgroup=gp.idgroup AND o.idproduttore=gp.idproduttore"
              ." WHERE gp.idproduttore= :idproduttore"
              ." AND gp.iduser_ref= :iduser_ref"
              ." AND gp.idgroup= :idgroup"
              ." ORDER BY o.archiviato, o.data_fine DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore, 'idgroup' => $idgroup, 'iduser_ref' => $iduser_ref));
        if($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }
    
    function getProdottiByIdOrdine_Gestione($idordine, $idproduttore) {
        // get elenco completo prodotti
        $sqlp = "SELECT p.*, cs.descrizione AS categoria FROM prodotti AS p LEFT JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat WHERE p.idproduttore= :idproduttore AND p.attivo='S'";
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
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, cs.descrizione AS categoria "
              ." FROM ordini_prodotti AS op "
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
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
    
    
    function getAllByIdgroup($idgroup, $idproduttore=null) {
        
        $arFilters = array('idgroup' => $idgroup);
        
        $sql = "SELECT o.*, p.*, u.nome, u.cognome "
             ." FROM ordini AS o "
             ." LEFT JOIN groups_produttori AS gp ON o.idgroup=gp.idgroup AND o.idproduttore=gp.idproduttore "
             ." LEFT JOIN produttori AS p ON gp.idproduttore=p.idproduttore "
             ." LEFT JOIN users AS u ON gp.iduser_ref=u.iduser "
             ." WHERE gp.idgroup= :idgroup";
        if(!is_null($idproduttore)) {
            $sql .= " AND gp.idproduttore= :idproduttore";
            $arFilters["idproduttore"] = $idproduttore;
        }
        $sql .= " ORDER BY o.archiviato, o.data_fine DESC";
        $sth = $this->db->prepare($sql);
        $sth->execute($arFilters);
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getProdottiOrdinatiByIdOrdine($idordine) {
        // get elenco prodotti disponibile per quest'ordine
        $sqlp = "SELECT p.*, op.costo AS costo_op, op.sconto, op.offerta, cs.descrizione AS categoria, "
              ." SUM(oup.qta) AS qta_ord, u.iduser, u.nome, u.cognome "
              ." FROM ordini_user_prodotti AS oup"
              ." JOIN users AS u ON oup.iduser=u.iduser"
              ." JOIN ordini_prodotti AS op ON oup.idprodotto=op.idprodotto AND oup.idordine=op.idordine"
              ." JOIN prodotti AS p ON op.idprodotto=p.idprodotto "
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat "
              ." WHERE op.idordine= :idordine"
              ." GROUP BY oup.iduser, oup.idprodotto";
        $sthp = $this->db->prepare($sqlp);
        $sthp->execute(array('idordine' => $idordine));
        $prodotti = $sthp->fetchAll(PDO::FETCH_OBJ);
        //Zend_Debug::dump($prodotti);die;
        return $prodotti;
    }
    
    
    function addProdottiToOrdine($idordine, $arVal) {
        $this->db->beginTransaction();
        
        // get All products of this order
        $prodotti = array();
        $sql = "SELECT idprodotto FROM ordini_prodotti WHERE idordine=".$this->db->quote($idordine);
        foreach ($this->db->query($sql) as $row) {
            $prodotti[] = $row["idprodotto"];
        }
        // prepare SQL INSERT
        $sth_insert = $this->db->prepare("INSERT INTO ordini_prodotti SET idprodotto= :idprodotto, idordine= :idordine, costo= :costo");
        // prepare SQL UPDATE
        $sth_update = $this->db->prepare("UPDATE ordini_prodotti SET costo= :costo WHERE idprodotto= :idprodotto AND idordine= :idordine");
        foreach($arVal AS $prodVal) {
            // prepare fields
            $fields = array(
                'idprodotto' => $prodVal["idprodotto"],
                'costo'      => (isset($prodVal["costo"]) ? $prodVal["costo"] : 0),
                'idordine' => $idordine
                );
            // check if EXISTS
            if(in_array($prodVal["idprodotto"], $prodotti)) {
                $sth_update->execute($fields);
            } else {
                $sth_insert->execute($fields);
            }
        }
        
        $this->db->commit();
    }
    
}
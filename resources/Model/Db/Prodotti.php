<?php

/**
 * Description of Model_Db_Prodotti
 * 
 * @author gullo
 */
class Model_Db_Prodotti extends MyFw_DB_Base {

    function __construct() {
        parent::__construct();
    }


    function getProdottoById($idprodotto) 
    {
        $sth_app = $this->db->prepare("SELECT *, descrizione AS descrizione_prodotto FROM prodotti WHERE idprodotto= :idprodotto");
        $sth_app->execute(array('idprodotto' => $idprodotto));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }
    
    function getProdottoByCodice($codice) 
    {
        $sth_app = $this->db->prepare("SELECT *, descrizione AS descrizione_prodotto FROM prodotti WHERE codice= :codice");
        $sth_app->execute(array('codice' => $codice));
        return $sth_app->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Elenco prodotti dell'anagrafica Produttore
     * 
     * @param int $idproduttore
     * @return array
     */
    function getProdottiByIdProduttore($idproduttore) {
        $sql = "SELECT p.*, "
              ." cs.descrizione AS categoria_sub, c.idcat, c.descrizione AS categoria "
              ." FROM prodotti AS p"
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat"
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE p.idproduttore= :idproduttore"
              ." ORDER BY c.descrizione, p.codice";
        //echo $sql; die;
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idproduttore' => $idproduttore));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Elenco prodotti di un Listino
     * 
     * @param int $idlistino
     * @return array
     */
    function getProdottiByIdListino($idlistino) {
        $sql = "SELECT p.*, "
              ." lp.idlistino, lp.descrizione_listino, lp.costo_listino, lp.note_listino, lp.attivo_listino, "
              ." cs.descrizione AS categoria_sub, c.idcat, c.descrizione AS categoria "
              ." FROM listini AS l "
              ." JOIN prodotti AS p ON l.idproduttore=p.idproduttore "
              ." LEFT JOIN listini_prodotti AS lp ON p.idprodotto=lp.idprodotto AND lp.idlistino= :idlistino"
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat"
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE l.idlistino= :idlistino"
              ." ORDER BY c.descrizione, p.codice";
        /**
         * @todo Qui mancano i filtri per i prodotti in PRODUCTION=S o proprietario tramite iduser_creator
         */
        // echo $sql; die;
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idlistino' => $idlistino));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Elenco prodotti di un Ordine
     * 
     * @param int $idlistino
     * @return array
     */
    function getProdottiByIdOrdine($idordine) {
        $sql = "SELECT p.*, prod.ragsoc AS ragsoc_produttore, "
              ." lp.idlistino, lp.descrizione_listino, lp.costo_listino, lp.note_listino, lp.attivo_listino, "
              ." op.costo_ordine, op.offerta_ordine, op.sconto_ordine, op.disponibile_ordine, "
              ." cs.descrizione AS categoria_sub, c.idcat, c.descrizione AS categoria, "
              ." op.idordine "
              ." FROM ordini_prodotti AS op "
              ." JOIN listini_prodotti AS lp ON lp.idlistino=op.idlistino AND lp.idprodotto=op.idprodotto "
              ." JOIN prodotti AS p ON lp.idprodotto=p.idprodotto"
              ." JOIN produttori AS prod ON p.idproduttore=prod.idproduttore "
              ." JOIN categorie_sub AS cs ON p.idsubcat=cs.idsubcat"
              ." JOIN categorie AS c ON cs.idcat=c.idcat "
              ." WHERE op.idordine= :idordine"
              ." ORDER BY c.descrizione, p.codice";
        // echo $sql; die;
        $sth = $this->db->prepare($sql);
        $sth->execute(array('idordine' => $idordine));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function getProdottiByIdSubCat($idsubcat) {
        
        $sth_app = $this->db->prepare("SELECT *, descrizione AS descrizione_prodotto FROM prodotti WHERE idsubcat= :idsubcat");
        $sth_app->execute(array('idsubcat' => $idsubcat));
        return $sth_app->fetchAll(PDO::FETCH_OBJ);
    }

    
    
    
/*****************
 * SAVE Procedures
 */
    
    private function _checkProdotti($prodotti)
    {
        if(is_array($prodotti)) {
            return $prodotti;
        } else if($prodotti instanceof Model_Prodotto_Mediator_MediatorInterface)
        {
            return array(0 => $prodotti);
        } else {
            throw new Exception("Prodotti must be Array or Prodotto(Mediator) object");
        }
    }
    
    public function updateProdotti($type, $p)
    {
        $prodotti = $this->_checkProdotti($p);
        switch ($type) {
            case "Anagrafica": return $this->updateProdottiAnagrafica($prodotti);
            case "Listino": return $this->updateProdottiListino($prodotti);
            case "Ordine": return $this->updateProdottiOrdine($prodotti);
        }
        throw new Exception("Type Prodotti '$type' does NOT EXISTS!");
    }
    
    private function updateProdottiAnagrafica($prodotti)
    {
        $this->db->beginTransaction();
        foreach ($prodotti AS $prodotto) {
            $arValues = $prodotto->getAnagraficaValues();
            $arValues["idprodotto"] = $prodotto->getIdProdotto();
            $sth = $this->db->prepareUpdateQueryByParams("prodotti", "idprodotto", $arValues);
            $res = $sth->execute($arValues);
            if(!$res) {
                return $this->db->rollBack();
            }
        }
        return $this->db->commit();
    }
    
    private function updateProdottiListino($prodotti)
    {
        $this->db->beginTransaction();
        foreach ($prodotti AS $prodotto) {
            $arValues = $prodotto->getListinoValues();
            $arValues["idlistino"] = $prodotto->getIdListino();
            $arValues["idprodotto"] = $prodotto->getIdProdotto();
            $sth = $this->db->prepareUpdateQueryByParams("listini_prodotti", array("idlistino", "idprodotto"), $arValues);
            $res = $sth->execute($arValues);
            if(!$res) {
                return $this->db->rollBack();
            }
        }
        return $this->db->commit();
    }
    
    private function updateProdottiOrdine($prodotti)
    {
        $this->db->beginTransaction();
        foreach ($prodotti AS $prodotto) {
            $arValues = $prodotto->getOrdineValues();
            $arValues["idordine"] = $prodotto->getIdOrdine();
            $arValues["idlistino"] = $prodotto->getIdListino();
            $arValues["idprodotto"] = $prodotto->getIdProdotto();
            $sth = $this->db->prepareUpdateQueryByParams("ordini_prodotti", array("idordine", "idlistino", "idprodotto"), $arValues);
            $res = $sth->execute($arValues);
            if(!$res) {
                return $this->db->rollBack();
            }
        }
        return $this->db->commit();
    }
    
}
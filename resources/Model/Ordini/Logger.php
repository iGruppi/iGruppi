<?php

/**
 * Description of Logger
 * 
 * @author gullo
 */
class Model_Ordini_Logger {
    
    static function LogToDB($idordine, $descrizione) 
    {
        $db = Zend_Registry::get("db");
        $sth = $db->prepare("INSERT INTO ordini_variazioni SET idordine= :idordine, data=NOW(), descrizione= :descrizione");
        return $sth->execute(array('idordine' => $idordine, 'descrizione' => $descrizione));
    }
    
    
    static function LogByField($field, array $data)
    {
        switch ($field) {
            case "costo_ordine":
                self::LogVariazionePrezzo($data["idordine"], $data["idprodotto"], $data["costo_ordine"], $data["costo_ordine"]);
                break;
            
            case "disponibile_ordine":
                self::LogVariazioneDisponibile($data["idordine"], $data["idprodotto"], $data["disponibile_ordine"]);
                break;
            default:
                
                break;
        }
    }
    
    static function LogVariazionePrezzo($idordine, $idprodotto, $pre, $new)
    {
        $pObj = new Model_Db_Prodotti();
        $prod = $pObj->getProdottoById($idprodotto);
        if(!is_null($prod))
        {
            $descProdotto = $prod->descrizione;
            $txt = "Il prezzo del prodotto <b>$descProdotto</b> è stato modificato, da $pre &euro; a <b>$new &euro;</b>";
            self::LogToDB($idordine, $txt);
        }
    }
    
    static function LogVariazioneDisponibile($idordine, $idprodotto, $disponibile)
    {
        $pObj = new Model_Db_Prodotti();
        $prod = $pObj->getProdottoById($idprodotto);
        if(!is_null($prod))
        {
            $descProdotto = $prod->descrizione;
            if($disponibile) {
                $txt = "Il prodotto <b>$descProdotto</b> è stato reso disponibile!</b>";
            } else {
                $txt = "Il prodotto <b>$descProdotto</b> è stato reso <b>NON</b> disponibile!</b>";                
            }
            self::LogToDB($idordine, $txt);
        }
    }
    
}
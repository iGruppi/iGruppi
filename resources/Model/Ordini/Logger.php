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
    
}
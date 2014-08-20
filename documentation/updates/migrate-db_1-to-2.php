<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH),
    realpath(APPLICATION_PATH . '/library'),
    realpath(APPLICATION_PATH . '/resources')
)));

/**********************************
 *  CONFIGURATION
 *  Impostare accesso ai 2 database: DB1 (versione 1.x) e DB2 (versione 2.x)
 */

// DB1: Database with iGruppi 1.x schema
$dsn1 = 'mysql:host=localhost;dbname=igruppi_demo';
$db1 = new PDO($dsn1, "root", "", array());

// DB2: Database with iGruppi 2.x schema (IT MUST BE EMPTY!)
$dsn2 = 'mysql:host=localhost;dbname=igruppi';
$db2 = new PDO($dsn2, "root", "", array());

/*
 *  END CONFIGURATION --------------
 **********************************/

$db1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



// disable Foreign key check for db2
$db2->query("SET FOREIGN_KEY_CHECKS=0");

/*
 * Users, Groups and Produttori

// Tables not changed
copyTable("users", "users");
copyTable("produttori", "produttori");
$db2->query("UPDATE produttori SET production='S'");
copyTable("groups", "groups");
copyTable("users_group", "users_group");
// REFERENTI di Gruppo
copyTable("groups_produttori", "referenti", array(
    'idgroup'       => 'idgroup',
    'idproduttore'  => 'idproduttore',
    'iduser_ref'    => 'iduser'
));
// REFERENTI Globali 
// TODO: Eventualmente da sistemare nel caso vi sia 1 solo referente globale!
copyTable("groups_produttori", "users_produttori", array(
    'idproduttore'  => 'idproduttore',
    'iduser_ref'    => 'iduser'
));
 */

/*
 * PRODOTTI (Tabelle base)

copyTable("prodotti", "prodotti");
copyTable("categorie", "categorie");
copyTable("categorie_sub", "categorie_sub", array(
    'idsubcat'      => 'idsubcat',
    'idcat'         => 'idcat',
    'idproduttore'  => 'idproduttore',
    'descrizione'   => 'descrizione'
));
$db2->query("UPDATE prodotti SET production='S'");
 */

/*
 * LISTINI Prodotti

$sql_p = "SELECT DISTINCT prod.idproduttore, p.ragsoc, up.iduser AS iduser_ref "
        . "FROM prodotti AS prod "
        . "LEFT JOIN produttori AS p ON prod.idproduttore=p.idproduttore "
        . "LEFT JOIN users_produttori AS up ON prod.idproduttore=up.idproduttore "
        . "GROUP BY prod.idproduttore";
$sth_p = $db2->prepare($sql_p);
$sth_p->execute();
if($sth_p->rowCount() > 0) {
    $recs = $sth_p->fetchAll(PDO::FETCH_ASSOC);
    foreach( $recs AS $ffp )
    {
        $idproduttore = $ffp["idproduttore"];
        $ragsoc = $ffp["ragsoc"];
        $iduser_ref = $ffp["iduser_ref"];
        
        // ADD NEW LISTINO
        $db2->query("INSERT INTO listini SET iduser_ref='$iduser_ref', descrizione='Listino imported: $ragsoc '");
        $idlistino = $db2->lastInsertId();
        $db2->query("INSERT INTO groups_listini SET idlistino= $idlistino, idgroup_master=1, idgroup_slave=1");
        
        $sth_pp = $db2->prepare("SELECT * FROM prodotti WHERE idproduttore= :idproduttore");
        $sth_pp->execute(array('idproduttore' => $idproduttore));
        if($sth_pp->rowCount() > 0) {
            $recpp = $sth_pp->fetchAll(PDO::FETCH_ASSOC);
            foreach( $recpp AS $fields )
            {
                $sth_pl = $db2->prepare("INSERT INTO prodotti_listini SET idlistino= :idlistino, idprodotto= :idprodotto, descrizione= :descrizione, costo= :costo, note= :note, attivo= :attivo");
                $sth_pl->execute(array('idlistino' => $idlistino, 'idprodotto' => $fields["idprodotto"], 'descrizione' => $fields["descrizione"], 'costo' => $fields["costo"], 'note' => $fields["note"], 'attivo' => $fields["attivo"]));
            }            
        }
    }
}
 */

/*
 *  ORDINI

$sth_o = $db1->prepare("SELECT * FROM ordini");
$sth_o->execute();
if($sth_o->rowCount() > 0) {
    $recs = $sth_o->fetchAll(PDO::FETCH_ASSOC);
    foreach( $recs AS $ffo )
    {
        // GET LISTINO
        $idproduttore = $ffo["idproduttore"];
        $sth1 = $db2->prepare("SELECT idlistino FROM prodotti_listini AS pl LEFT JOIN prodotti AS p ON pl.idprodotto=p.idprodotto WHERE p.idproduttore= :idproduttore LIMIT 0,1");
        $sth1->execute(array('idproduttore' => $idproduttore));
        if($sth1->rowCount() > 0) {
            $rec1 = $sth1->fetch(PDO::FETCH_ASSOC);
            $idlistino = $rec1["idlistino"];
        // Insert ORDINE
            $idordine = $ffo["idordine"];
            $data_inviato = is_null($ffo["data_inconsegna"]) ? "NULL" : "'".$ffo["data_inconsegna"]."'";
            $data_arrivato = is_null($ffo["data_consegnato"]) ? "NULL" : "'".$ffo["data_consegnato"]."'"; // it does not exists in db1
            $data_consegnato = is_null($ffo["data_consegnato"]) ? "NULL" : "'".$ffo["data_consegnato"]."'";
            $db2->query("INSERT INTO ordini "
                    . "(`idordine`, `idlistino`, `idgroup_slave`, `data_inizio`, `data_fine`, `data_inviato`, `data_arrivato`, `data_consegnato`, `archiviato`, `note_consegna`, `costo_spedizione`) VALUES "
                    . "('".$idordine."', '".$idlistino."', '".$ffo["idgroup"]."', '".$ffo["data_inizio"]."', '".$ffo["data_fine"]."', ".$data_inviato.", ".$data_arrivato.", ".$data_consegnato.", '".$ffo["archiviato"]."', '".$ffo["note_consegna"]."', '".$ffo["costo_spedizione"]."')");
            
        // Insert records in ordini_user
            $sth3 = $db1->prepare("SELECT DISTINCT iduser FROM ordini_user_prodotti WHERE idordine='$idordine'");
            $sth3->execute();
            if($sth3->rowCount() > 0) {
                $recs3 = $sth3->fetchAll(PDO::FETCH_ASSOC);
                $iii = 0;
                foreach( $recs3 AS $ffi3 )
                {
                    $db2->query("INSERT INTO ordini_user SET iduser='".$ffi3["iduser"]."', idordine='$idordine', note='note $iii'");
                    $iii++;
                }
            }
            
        // Insert records in ordini_prodotti
            $sth4 = $db1->prepare("SELECT * FROM ordini_prodotti WHERE idordine='$idordine'");
            $sth4->execute();
            if($sth4->rowCount() > 0) {
                $recs4 = $sth4->fetchAll(PDO::FETCH_ASSOC);
                foreach( $recs4 AS $ffp4 )
                {
                    $db2->query("INSERT INTO `ordini_prodotti` "
                            . "(`idordine`, `idlistino`, `idprodotto`, `costo`, `offerta`, `sconto`, `disponibile`) VALUES "
                            . "($idordine, $idlistino, '".$ffp4["idprodotto"]."', '".$ffp4["costo"]."', '".$ffp4["offerta"]."', '".$ffp4["sconto"]."', '".$ffp4["disponibile"]."')");
                }
            }
            
        // Insert records in ordini_user_prodotti
            $sth5 = $db1->prepare("SELECT * FROM ordini_user_prodotti WHERE idordine='$idordine'");
            $sth5->execute();
            if($sth5->rowCount() > 0) {
                $recs5 = $sth5->fetchAll(PDO::FETCH_ASSOC);
                foreach( $recs5 AS $ffu5 )
                {
                    $db2->query("INSERT INTO `ordini_user_prodotti` "
                            . "(`iduser`, `idordine`, `idlistino`, `idprodotto`, `qta`, `qta_reale`, `data_ins`) VALUES "
                            . "('".$ffu5["iduser"]."', $idordine, $idlistino, '".$ffu5["idprodotto"]."', '".$ffu5["qta"]."', '".$ffu5["qta_reale"]."', '".$ffu5["data_ins"]."')");
                }
            }
                    
        }
    }
}
 */

// RE-enable Foreign key check for db2
$db2->query("SET FOREIGN_KEY_CHECKS=1");


/*******************************
 *  PURPOSE FUNCTIONS
 */

function copyTable($table_db1, $table_db2, $arRef=null)
{
    global $db1, $db2;
    $sth = $db1->prepare("SELECT * FROM $table_db1");
    $sth->execute();
    if($sth->rowCount() > 0) {
        $recs = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach( $recs AS $fields )
        {
            if(count($fields) > 0) {
                $sql = "INSERT $table_db2 SET";
                
                // without fields references
                if( is_null($arRef) ) {
                    foreach ($fields as $field => $value) {
                        $sql .= " $field= :$field,";
                    }
                } else {
                    $fields_old = $fields;
                    $fields = array();
                    foreach ($arRef as $field1 => $field2) {
                        $sql .= " $field2= :$field2,";
                        $fields[$field2] = $fields_old[$field1];
                    }
                }
                $sql = substr($sql, 0, -1);
                $sth = $db2->prepare($sql);
                $sth->execute($fields);
            } else {
                throw new Exception("SQL UPDATE ERROR: No Fields!");
            }
        }
    }
}

function dumpArray($a)
{
    echo "<pre>";
    print_r($a);
    echo "</pre>";
}

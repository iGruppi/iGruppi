<?php

/**
 * Description of AppsDomains Controller
 * 
 * @author gullo
 */
class Controller_GestioneOrdini extends MyFw_Controller {
    
    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }
    
    function indexAction() {

        $idproduttore = $this->getParam("idproduttore");
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        $ordObj = new Model_Ordini();
        $listOrd = $ordObj->getAllByIdProduttore($idproduttore, $this->_userSessionVal->idgroup, $this->_iduser);
        if(count($listOrd) > 0) {
            foreach($listOrd AS &$ordine) {
                $ordine->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);
            }
        }        
        $this->view->list = $listOrd;
    }
    
    function newAction() {

        $idproduttore = $this->getParam("idproduttore");
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/new/idproduttore/$idproduttore");
        $form->setValue("idgp", $produttore->idgp);
        // remove useless fields
        $form->removeField("archiviato");
        $form->removeField("idordine");

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Ordine in stato NEW
                $fValues = $form->getValues();
                $dt_inizio = new Zend_Date($fValues["data_inizio"], "dd/mm/yyyy");
                $fValues["data_inizio"] = $dt_inizio->toString("yyyy-mm-dd") . " 00:00:00"; // set default START time
                $dt_fine = new Zend_Date($fValues["data_fine"], "dd/mm/yyyy");
                $fValues["data_fine"] = $dt_fine->toString("yyyy-mm-dd") . " 23:59:59"; // set default END time
                $idordine = $this->getDB()->makeInsert("ordini", $fValues);
                
                $this->redirect("gestione-ordini", "index", array("idproduttore" => $idproduttore));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    function editAction() {

        $idordine = $this->getParam("idordine");
        $orderObj = new Model_Ordini();
        $ordine = $orderObj->getByIdOrdine($idordine);

        $idproduttore = $ordine->idproduttore;
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/edit/idordine/$idordine");
        $form->setValue("idgp", $produttore->idgp);
        $form->setValue("idordine", $idordine);

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Ordine in stato NEW
                $fValues = $form->getValues();
                $dt_inizio = new Zend_Date($fValues["data_inizio"], "dd/mm/yyyy");
                $fValues["data_inizio"] = $dt_inizio->toString("yyyy-mm-dd") . " 00:00:00"; // set default START time
                $dt_fine = new Zend_Date($fValues["data_fine"], "dd/mm/yyyy");
                $fValues["data_fine"] = $dt_fine->toString("yyyy-mm-dd") . " 23:59:59"; // set default END time
                $this->getDB()->makeUpdate("ordini", "idordine", $fValues );
                
                $this->redirect("gestione-ordini", "index", array("idproduttore" => $idproduttore));
            }
        } else {
            // get only dates without time
            $dt_inizio = new Zend_Date($ordine->data_inizio, "yyyy-mm-dd HH:mm:ss");
            $ordine->data_inizio = $dt_inizio->toString("dd/mm/yyyy");
            $dt_fine = new Zend_Date($ordine->data_fine, "yyyy-mm-dd HH:mm:ss");
            $ordine->data_fine = $dt_fine->toString("dd/mm/yyyy");
            
            $form->setValues($ordine);
        }
        
        // set Form in the View
        $this->view->form = $form;
    }

    function prodottiAction() {
        
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);;
               
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        # TODO : Inserire controllo per i furbi che vogliono visualizzare/cancellare ordini non loro
        
        // SAVE FORM
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            
            $prod_sel = isset($fv["prod_sel"]) ? $fv["prod_sel"] : array();
            $prodotto = isset($fv["prodotto"]) ? $fv["prodotto"] : array();
            if(count($prod_sel) > 0) {
                // delete all records in ordini_prodotti
                $this->getDB()->query("DELETE FROM ordini_prodotti WHERE idordine='$idordine'");
                // insert product selected
                foreach ($prod_sel as $idprodotto => $selected) {
                    if( $selected == "S") {
                        // prepare SQL INSERT
                        $sql = "INSERT INTO ordini_prodotti SET idprodotto= :idprodotto, idordine= :idordine, costo= :costo";
                        $sth = $this->getDB()->prepare($sql);
                        $costo = isset($prodotto[$idprodotto]) ? $prodotto[$idprodotto] : 0;
                        $fields = array('idprodotto' => $idprodotto, 'idordine' => $idordine, 'costo' => $costo);
                        $sth->execute($fields);
                    }
                }
                
                $this->view->updated = true;
            }
        }

        // elenco prodotti (aggiornato dopo eventuale POST)
        $this->view->list = $ordObj->getProdottiByIdOrdine_Gestione($idordine, $ordine->idproduttore);
        //Zend_Debug::dump($this->view->list);
    }
    
    function dettaglioAction() {
        
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);;
               
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        $listOrd = $ordObj->getProdottiOrdinatiByIdOrdine($idordine);
        
        // CREO array RIEPILOGO prodotti ordinati e DETTAGLIO
        $riepilogo = array();
        $dettaglio = array();
        if(count($listOrd) > 0) {
            foreach ($listOrd as $value) {
                $idprodotto = $value->idprodotto;
                
            // RIEPILOGO
                if(!isset($riepilogo[$idprodotto])) {
                    $riepilogo[$idprodotto] = array(
                        'descrizione' => $value->descrizione,
                        'udm' => $value->udm,
                        'costo_op' => $value->costo_op,
                        'sconto' => $value->sconto,
                        'offerta' => $value->offerta,
                        'qta_ord' => 0,
                    );
                }
                $riepilogo[$idprodotto]["qta_ord"] += $value->qta_ord;
                
            // DETTAGLIO
                $iduser = $value->iduser;
                if(!isset($dettaglio[$iduser])) {
                    $dettaglio[$iduser] = array(
                        'nome' => $value->nome,
                        'cognome' => $value->cognome,
                        'prodotti' => array(),
                    );
                }
                $dettaglio[$iduser]["prodotti"][] = array(
                        'idprodotto' => $value->idprodotto,
                        'descrizione' => $value->descrizione,
                        'udm' => $value->udm,
                        'costo_op' => $value->costo_op,
                        'qta_ord' => $value->qta_ord,
                );
                
            }
        }
        //Zend_Debug::dump((object)$riepilogo);die;
        $this->view->riepilogo = (object)$riepilogo;
        $this->view->dettaglio = $dettaglio;
    }
    
}
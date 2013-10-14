<?php

/**
 * Description of GestioneOrdini Controller
 * 
 * @author gullo
 */
class Controller_GestioneOrdini extends MyFw_Controller {
    
    private $_userSessionVal;
    private $_iduser;
    private $_produttore;
    private $_ordine;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
        
        // Try to GET Produttore
        $idproduttore = $this->getParam("idproduttore");
        if(is_null($idproduttore)) {
            // Try to GET Ordine
            $idordine = $this->getParam("idordine");
            if(!is_null($idordine)) {
                $ordObj = new Model_Ordini();
                $ordine = $ordObj->getByIdOrdine($idordine);
                if(!is_null($ordine)) {
                    $this->_ordine = $ordine;
                    $idproduttore = $ordine->idproduttore;
                }
            }
        }
        if(is_null($idproduttore)) {
            $this->redirect("index", "error", array('code' => 404));
        }
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        $this->_produttore = $produttore;
        // check REFERENTE, controllo per i furbi (non Referenti)
        $user_ref = new Model_Produttori_Referente($produttore->iduser_ref);
        if(!$user_ref->is_Referente()) {
            $this->redirect("index", "error", array('code' => 404));
        }
    }
    
    function indexAction() {
        
        // Get updated if it is set
        $this->view->updated = $this->getParam("updated");        
        // SET Produttore
        $this->view->produttore = $this->_produttore;
        
        $ordObj = new Model_Ordini();
        $listOrd = $ordObj->getAllByIdProduttore($this->_produttore->idproduttore, $this->_userSessionVal->idgroup, $this->_iduser);
        if(count($listOrd) > 0) {
            foreach($listOrd AS &$ordine) {
                $ordine->statusObj = new Model_Ordini_Status($ordine);
            }
        }
        $this->view->list = $listOrd;
    }
    
    function newAction() {
        
        $this->view->produttore = $this->_produttore;
        
        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/new/idproduttore/".$this->_produttore->idproduttore);
        $form->setValue("idgroup", $this->_userSessionVal->idgroup);
        $form->setValue("idproduttore", $this->_produttore->idproduttore);
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
                
                // Add ALL prodotti by Default!
                $prodObj = new Model_Prodotti();
                $prodotti = $prodObj->getProdottiByIdProduttore($this->_produttore->idproduttore, 'S');
                if(count($prodotti) > 0) {
                    $ordObj = new Model_Ordini();
                    foreach($prodotti AS $prodotto) {
                        $arVal[] = array('idprodotto' => $prodotto->idprodotto, 'costo' => $prodotto->costo);
                    }
                    $ordObj->addProdottiToOrdine($idordine, $arVal);
                }
                
                $this->redirect("gestione-ordini", "index", array("idproduttore" => $this->_produttore->idproduttore, "updated" => true));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    function editAction() {

        $this->view->produttore = $this->_produttore;

        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/edit/idordine/".$this->_ordine->idordine);
        $form->setValue("idgroup", $this->_userSessionVal->idgroup);
        $form->setValue("idproduttore", $this->_produttore->idproduttore);
        $form->setValue("idordine", $this->_ordine->idordine);

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
                // REDIRECT
                $this->redirect("gestione-ordini", "index", array("idproduttore" => $this->_produttore->idproduttore, "updated" => true));
            }
        } else {
            // get only dates without time
            $dt_inizio = new Zend_Date($this->_ordine->data_inizio, "yyyy-mm-dd HH:mm:ss");
            $this->_ordine->data_inizio = $dt_inizio->toString("dd/mm/yyyy");
            $dt_fine = new Zend_Date($this->_ordine->data_fine, "yyyy-mm-dd HH:mm:ss");
            $this->_ordine->data_fine = $dt_fine->toString("dd/mm/yyyy");
            
            $form->setValues($this->_ordine);
        }
        
        // set Form in the View
        $this->view->form = $form;
    }

    function prodottiAction() {
        
        $idordine = $this->_ordine->idordine;
        $this->view->ordine = $this->_ordine;
        $this->view->produttore = $this->_produttore;
        $this->view->statusObj = new Model_Ordini_Status($this->_ordine);
        $this->view->updated = false;
        
        // SAVE FORM
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            
            $prod_sel = isset($fv["prod_sel"]) ? $fv["prod_sel"] : array();
            $prodotto = isset($fv["prodotto"]) ? $fv["prodotto"] : array();
            $arVal = array();
            if(count($prod_sel) > 0) {
                // insert products selected
                foreach ($prod_sel as $idprodotto => $selected) {
                    if( $selected == "S") {
                        $arVal[] = array('idprodotto' => $idprodotto, 'costo' => (isset($prodotto[$idprodotto]) ? $prodotto[$idprodotto] : 0));
                    } else {
                        // delete all records in ordini_prodotti
                        $this->getDB()->query("DELETE FROM ordini_prodotti WHERE idordine='$idordine' AND idprodotto='$idprodotto'");
                    }
                }
                if(count($arVal) > 0) {
                    $ordObj->addProdottiToOrdine($idordine, $arVal);
                }
                
                $this->view->updated = true;
            }
        }

        // elenco prodotti (aggiornato dopo eventuale POST)
        $ordObj = new Model_Ordini();
        $this->view->list = $ordObj->getProdottiByIdOrdine_Gestione($idordine, $this->_ordine->idproduttore);
        //Zend_Debug::dump($this->view->list);
    }
    
    function dettaglioAction() {
        
        $this->view->ordine = $this->_ordine;
        $this->view->statusObj = new Model_Ordini_Status($this->_ordine);
        $this->view->produttore = $this->_produttore;
        
        $ordObj = new Model_Ordini();
        $listOrd = $ordObj->getProdottiOrdinatiByIdOrdine($this->_ordine->idordine);
        
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
    
    function inviaAction() {
        $this->view->ordine = $this->_ordine;
        $this->view->statusObj = new Model_Ordini_Status($this->_ordine);
        $this->view->produttore = $this->_produttore;
        
        // TODO: Invia email...
    }
    
}
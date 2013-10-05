<?php
/**
 * Gestione Ordini per utenti
 *
 * @author gullo
 */
class Controller_Ordini extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() {
        
        // init filters array
        $filters = array();
        
        // init Filters class for View
        $fObj = new Model_Ordini_Filters($filters);
        $fObj->setUrlBase("/ordini/index");
        
        // SET idproduttore FILTER
        $fObj->setFilterByField("idproduttore", $this->getParam("idproduttore"));
        // SET stato FILTER
        $fObj->setFilterByField("stato", $this->getParam("stato"));
        // SET periodo FILTER
        //$fObj->setFilterByField("periodo", $this->getParam("periodo"));
        
        $this->view->fObj = $fObj;
        //Zend_Debug::dump($fObj->getFilters());
        // set elenco Stati
        $this->view->statusArray = Model_Ordini_Status::getArrayStatus();
        
        // set elenco produttori
        $prodObj = new Model_Produttori();
        $produttori = $prodObj->getProduttoriByIdGroup($this->_userSessionVal->idgroup);
        $this->view->produttori = $produttori;
        // Create array Categorie prodotti for Produttori
        $catObj = new Model_Categorie();
        $arCat = $catObj->getSubCategoriesByIdgroup($this->_userSessionVal->idgroup);
        $this->view->arCat = $arCat;
        
        $ordiniObj = new Model_Ordini();
        $listOrd = $ordiniObj->getAllByIdgroupWithFilter($this->_userSessionVal->idgroup, $fObj->getFilters());
        // add Status model to Ordini
        if(count($listOrd) > 0) {
            foreach($listOrd AS &$ordine) {
                $ordine->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);
            }
        }
        $this->view->list = $listOrd;
    }
    
    function viewdettaglioAction() {
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);;

        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        // elenco prodotti
        $this->view->list = $ordObj->getProdottiByIdOrdine($idordine, $ordine->idproduttore, $this->_iduser);
    }

    function ordinaAction() {
        
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);;

        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        # TODO : Inserire controllo per i furbi che vogliono visualizzare/cancellare ordini non loro
        
        // Reset updated flag
        $this->view->updated = false;
        
        // SAVE FORM
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            
            $prod_qta = isset($fv["prod_qta"]) ? $fv["prod_qta"] : array();
            if(count($prod_qta) > 0) {
                // delete all records in ordini_user_prodotti
                $this->getDB()->query("DELETE FROM ordini_user_prodotti WHERE iduser='".$this->_iduser."' AND idordine='$idordine'");
                // insert product selected
                foreach ($prod_qta as $idprodotto => $qta) {
                    if( $qta > 0) {
                        // prepare SQL INSERT
                        $sql = "INSERT INTO ordini_user_prodotti SET iduser= :iduser, idprodotto= :idprodotto, idordine= :idordine, qta= :qta, data_ins=NOW()";
                        $sth = $this->getDB()->prepare($sql);
                        $fields = array('iduser' => $this->_iduser, 'idprodotto' => $idprodotto, 'idordine' => $idordine, 'qta' => $qta);
                        $sth->execute($fields);
                    }
                }
                
                $this->view->updated = true;
            }
        }

        // elenco prodotti (aggiornato dopo eventuale POST)
        $listProd = $ordObj->getProdottiByIdOrdine($idordine, $ordine->idproduttore, $this->_iduser);
        // rebuild array
        $subCat = array();
        $prodotti = array();
        if(count($listProd) > 0) {
            foreach ($listProd as $key => $value) {
                $prodotti[$value->idcat][$value->idsubcat][$value->idprodotto] = $value;
                $subCat[$value->idcat]["categoria"] = $value->categoria;
                $subCat[$value->idcat]["subcat"][$value->idsubcat] = $value->categoria_sub;
            }
        }
        
        $this->view->listProdotti = $prodotti;
        $this->view->listSubCat = $subCat;
        //Zend_Debug::dump($subCat);
    }

}
?>

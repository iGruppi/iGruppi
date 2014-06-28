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
                $ordine->statusObj = new Model_Ordini_Status($ordine);
            }
        }
        $this->view->list = $listOrd;
    }
    
    function viewdettaglioAction() {
        $idordine = $this->getParam("idordine");
        
        // INIT Ordine
        $ordObj = new Model_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine);

        // GET PRODUTTORE
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        // GET PRODUCTS LIST with Qta Ordered
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($idordine);
        // init Calcoli Utente class
        $cuObj = new Model_Ordini_Calcoli_Utenti();
        $cuObj->setOrdObj($ordine);
        $cuObj->setProdotti($listProdOrdered);
        $this->view->listaProdotti = $cuObj->getProdottiByIduser($this->_iduser);
        // Costo di SPEDIZIONE
        $this->view->costo_spedizione = $cuObj->getSpedizione()->getCostoSpedizioneRipartitoByIduser($this->_iduser);
        $this->view->totale_ordine = $cuObj->getTotaleByIduser($this->_iduser);
        $this->view->totale_con_spedizione = $cuObj->getTotaleConSpedizioneByIduser($this->_iduser);
//        Zend_Debug::dump($this->view->listaProdotti);die;
    }

    
    function ordinaAction() {
        
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Ordini();
        
        // SAVE FORM if there is POST data
        $this->view->updated = false;
        if($this->getRequest()->isPost()) 
        {
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            $prod_qta = isset($fv["prod_qta"]) ? $fv["prod_qta"] : array();
            $this->view->updated = $ordObj->setQtaProdottiForOrdine($idordine, $this->_iduser, $prod_qta);
        }
        
        // INIT Ordine
        $ordine = $ordObj->getByIdOrdine($idordine);
        
        // Validate ORDINE for this GROUP
        if(is_null($ordine) || $ordine->idgroup != $this->_userSessionVal->idgroup) 
        {
            $this->redirect("ordini");
        }
        $this->view->ordine = $ordine;
        // Check ORDINE Status (can Order Products?)
        $statusObj = new Model_Ordini_Status($ordine);
        if(!$statusObj->can_OrderProducts()) 
        {
            $this->redirect("ordini");
        }
        $this->view->statusObj = $statusObj;
        
        // GET PRODUTTORE
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        // GET PRODUCTS LIST with Qta Ordered
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($idordine);
        
        // init Calcoli Utente class
        $cuObj = new Model_Ordini_Calcoli_Utenti();
        $cuObj->setOrdObj($ordine);
        $cuObj->setProdotti($listProdOrdered);
        $this->view->cuObj = $cuObj;
        $this->view->prodottiIduser = $cuObj->getProdottiByIduser($this->_iduser);
        
        // ORGANIZE by category and subCat
        $scoObj = new Model_Prodotti_SubCatOrganizer($listProdOrdered);
        //Zend_Debug::dump($scoObj);
        $this->view->listProdotti = $scoObj->getListProductsCategorized();
        $this->view->listSubCat = $scoObj->getListCategories();
    }

}
?>

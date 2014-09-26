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
        
        // init Filters class for View
        $fObj = new Model_Ordini_Filters();
        $fObj->setUrlBase("/ordini/index");
        
        // SET idproduttore FILTER
        $fObj->setFilterByField("idproduttore", $this->getParam("idproduttore"));
        // SET stato FILTER
        $fObj->setFilterByField("stato", $this->getParam("stato"));
        
        $this->view->fObj = $fObj;
        
        // set elenco Stati
        $this->view->statusArray = Model_Ordini_State_OrderFactory::getOrderStatesArray();
        
        // build list Ordini
        $ordiniObj = new Model_Db_Ordini();
        $listOrd = $ordiniObj->getAllByIdgroupWithFilter($this->_userSessionVal->idgroup, $fObj->getFilters());
        //Zend_Debug::dump($listOrd);die;
        $cObj = new Model_Db_Categorie();
        // create array of Ordini
        $ordini = array();
        if(count($listOrd) > 0) {
            foreach($listOrd AS $ordine) 
            {
                // BUILD Ordine object with a Chain of objects
                $mooObj = new Model_Ordini_Ordine();
                //$mooObj->enableDebug();
                $mooObj->appendDati();
                $mooObj->appendCategorie();
                
                // init Dati by stdClass
                $mooObj->initDati_ByObject($ordine);
                // ADD StatesOrderFactory to the Chain
                $mooObj->appendStatesOrderFactory();
                
                // init Categorie by IdOrdine
                $categorie = $cObj->getCategoriesByIdOrdine($mooObj->getIdOrdine());
                $mooObj->initCategorie_ByObject($categorie);
                //Zend_Debug::dump($catObj);
                
                
                // add Ordine object to the array
                $ordini[] = $mooObj;
            }
        }
        
        $this->view->ordini = $ordini;
        
    }
    
    function viewdettaglioAction() {
        $idordine = $this->getParam("idordine");
        
        // INIT Ordine
        $ordObj = new Model_Db_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine);

        // GET PRODUTTORE
        $produttoreObj = new Model_Db_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore);
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
        $ordObj = new Model_Db_Ordini();
        
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
        if(!$statusObj->canUser_OrderProducts()) 
        {
            $this->redirect("ordini");
        }
        $this->view->statusObj = $statusObj;
        
        // GET PRODUTTORE
        $produttoreObj = new Model_Db_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore);
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

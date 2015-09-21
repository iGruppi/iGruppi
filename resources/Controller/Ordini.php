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
        $stato = $this->getParam("stato");
        if(is_null($stato)) {
            $stato = "Aperto";
        }
        $fObj->setFilterByField("stato", $stato);
        
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
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                //$mooObj->enableDebug();
                $mooObj->appendDati();
                $mooObj->appendCategorie();
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );
                
                // init Dati by stdClass
                $mooObj->initDati_ByObject($ordine);
                
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
        
        // build Ordine
        $mooObj = new Model_Ordini_Ordine( new Model_AF_UserOrdineFactory() );
        // build & init DATI Ordine
        $mooObj->appendDati()->initDati_ByObject($ordine);
        // build & init STATE Ordine
        $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );

        // build & init Gruppi
        $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
        $mooObj->setMyIdGroup($this->_userSessionVal->idgroup);
        
        // creo elenco prodotti
        $prodottiModel = new Model_Db_Prodotti();
        $listProd = $prodottiModel->getProdottiByIdOrdine($idordine);
        
        // build & init CATEGORIE
        $mooObj->appendCategorie()->initCategorie_ByObject($listProd);

        // build & init PRODOTTI
        $mooObj->appendProdotti()->initProdotti_ByObject($listProd);

        // set Ordine in the View by default
        $this->view->ordine = $mooObj;
        
        // GET PRODUCTS LIST with Qta Ordered
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordineAndIdgroup($mooObj->getIdOrdine(),$this->_userSessionVal->idgroup);

        // SET ORDINE e PRODOTTI
        $ordCalcObj = new Model_Ordini_CalcoliDecorator($mooObj, $this->_userSessionVal->idgroup);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
//        Zend_Debug::dump($this->view->listaProdotti);die;
        
        // Set my iduser to View
        $this->view->iduser = $this->_iduser;
    }

    
    function ordinaAction() {
        
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Db_Ordini();
        // INIT Ordine
        $ordine = $ordObj->getByIdOrdine($idordine);
        // Validate ORDINE for this GROUP
        /**
         *  @todo
         * Qui mancano sicuramente i controlli per verificare se può o meno aprire quest'ordine!
         */
        if(is_null($ordine)) 
        {
            $this->redirect("ordini");
        }
        
        // build Ordine
        $mooObj = new Model_Ordini_Ordine( new Model_AF_UserOrdineFactory() );
        // build & init DATI Ordine
        $mooObj->appendDati()->initDati_ByObject($ordine);
        // build & init STATE Ordine
        $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );

        // build & init Gruppi
        $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
        $mooObj->setMyIdGroup($this->_userSessionVal->idgroup);
        
        // creo elenco prodotti
        $prodottiModel = new Model_Db_Prodotti();
        $listProd = $prodottiModel->getProdottiByIdOrdine($idordine);
        
        // build & init CATEGORIE
        $mooObj->appendCategorie()->initCategorie_ByObject($listProd);

        // build & init PRODOTTI
        $mooObj->appendProdotti()->initProdotti_ByObject($listProd);

        // set Ordine in the View by default
        $this->view->ordine = $mooObj;
        
        // Check ORDINE Status (can Order Products?)
        if(!$mooObj->canUser_OrderProducts()) 
        {
            $this->redirect("ordini");
        }
        
        // GET PRODUCTS LIST with Qta Ordered
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordineAndIdgroup($mooObj->getIdOrdine(), $this->_userSessionVal->idgroup);

        // SET ORDINE e PRODOTTI
        $ordCalcObj = new Model_Ordini_CalcoliDecorator($mooObj, $this->_userSessionVal->idgroup);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
        
        // Set my iduser to View
        $this->view->iduser = $this->_iduser;
    }

    
    function updateorderAction()
    {
        Zend_Registry::get("layout")->disableDisplay();
        
        // get values
        $idordine = $this->getParam("idordine");
        $idprodotto = $this->getParam("idprodotto");
        $idlistino = $this->getParam("idlistino");
        $qta = $this->getParam("qta");
        
        // UPDATE qta for this Prodotto
        $ordObj = new Model_Db_Ordini();
        $result = $ordObj->setQtaProdottoForOrdine($idordine, $idlistino, $idprodotto, $this->_iduser, $qta);
        //Zend_Debug::dump($result);
        echo json_encode($result);
    }
}

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
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($mooObj->getIdOrdine());

        // SET ORDINE e PRODOTTI
        $ordCalcObj = new Model_Ordini_Calcoli_Utenti();
        $ordCalcObj->setOrdine($mooObj);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
        
    }

    
    function testAction()
    {
        
        Zend_Registry::get("layout")->disableDisplay();
        
        $sql = "SELECT cs.idsubcat, cs.descrizione AS categoria_sub, c.idcat, c.descrizione AS categoria, prod.idproduttore, prod.ragsoc AS ragsoc_produttore "
              ."FROM categorie_sub AS cs "
              ."LEFT JOIN categorie AS c ON cs.idcat=c.idcat "
              ."LEFT JOIN produttori AS prod ON cs.idproduttore=prod.idproduttore "
              ."WHERE 1";
        $sth = $this->getDB()->prepare($sql);
        $sth->execute();
        // set Categorie in Listini object
        $categorie = $sth->fetchAll(PDO::FETCH_OBJ);
        $catObj = new Model_Categorie();
        $catObj->initDatiByObject($categorie);
        $list = $catObj->getProduttoriList();
        Zend_Debug::dump( $list ); 
        //Zend_Debug::dump( $catObj ); 
        die;
        
        
    }
}

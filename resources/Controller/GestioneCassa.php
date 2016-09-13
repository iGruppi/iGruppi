<?php
/**
 * Description of GESTIONE CASSA
 *
 * @author gullo
 */
class Controller_GestioneCassa extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;    
    
    function _init() 
    {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
        
        // Check Permission to MANAGE CASSA (only for Contabile)
        if(!$this->_userSessionVal->aclUserObject->canManageCassa()) {
            $this->redirect("index", "error", array('code' => 401));
        }
    }

    function indexAction() 
    {
        // init the SimplePager
        $page = is_null($this->getParam("page")) ? 0 : $this->getParam("page");
        $view = is_null($this->getParam("view")) ? 10 : $this->getParam("view");
        $sPager = new MyFw_SimplePager();
        $sPager->setPage($page);
        $sPager->setView($view);
        $sPager->setURL('/gestione-cassa/index');
        
        $cassaModel = new Model_Db_Cassa();
        $movRecs = $cassaModel->getUltimiMovimentiByIdgroup($this->_userSessionVal->idgroup, $sPager->getSQLStartNumber(), $sPager->getSQLLimitNumber());
        $movimenti = array();
        if(count($movRecs) > 0)
        {
            foreach ($movRecs as $movimento) {
                $movimenti[] = new Model_Cassa_Movimento($movimento);
            }
        }
        $this->view->movimenti = $movimenti;
        
        // set num_result in SimplePager
        $sPager->setNumResults(count($movimenti));
        $this->view->sPager = $sPager;
    }
    

    function addAction() 
    {
        $form = new Form_Cassa();
        $form->setAction("/gestione-cassa/add");
        // remove useless fields
        $form->removeField("idmovimento");
        $form->removeField("idordine");
        
        if($this->getRequest()->isPost()) 
        {
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            // check data (set NOW as default)
            if($fv["data"] == "") {
                $fv["data"] = date("d/m/Y H:i");
            }
            
            if( $form->isValid($fv) ) 
            {
                $values = $form->getValues();

                // ADD NEW Movimento
                $idmovimento = $this->getDB()->makeInsert("cassa", $values);

                // REDIRECT to INDEX
                $this->redirect("gestione-cassa", "index");
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    
    function editAction() {
        
        $idmovimento = $this->getParam("idmovimento");
        if(is_null($idmovimento)) 
        {
            $this->redirect("gestione-cassa", "index");
        }
        
        $cassaObj = new Model_Db_Cassa();
        $movValues = $cassaObj->getMovimentoById($idmovimento);
        $movimento = new Model_Cassa_Movimento($movValues);
        
        $form = new Form_Cassa();
        $form->setAction("/gestione-cassa/edit/idmovimento/$idmovimento");
        
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // save Prodotto, after overwriting the fields values with form values
                $this->getDB()->makeUpdate("cassa", "idmovimento", $form->getValues());
                // REDIRECT
                $this->redirect("gestione-cassa", "index");
            }
        } else {
            $form->setValues($movValues);
            $form->setValue("data", $movimento->getData(MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW));            
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
    }

    
    function ordertocloseAction()
    {
        $filter = $this->getParam("filter");
        if(is_null($filter)) 
        {
            $filter = "PRI"; // DEFAULT value
        }
        $this->view->filter = $filter;
        
        $ordObj = new Model_Db_Ordini();
        $cObj = new Model_Db_Categorie();
        $listOrd = $ordObj->getOrdiniToClose($this->_userSessionVal->idgroup);
        $ordini = array();
        if(count($listOrd) > 0) {
            foreach($listOrd AS $ordine) {
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                $mooObj->appendDati()->initDati_ByObject($ordine);
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );
                
                // build & init Gruppi
                $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
                $mooObj->setMyIdGroup($this->_userSessionVal->idgroup);
                
                // set Categories in Ordine object
                $categorie = $cObj->getCategoriesByIdOrdine( $mooObj->getIdOrdine() );
                $mooObj->appendCategorie()->initCategorie_ByObject($categorie);
                // add Ordine to the list
                $ordini[] = $mooObj;
            }
        }
        $this->view->ordini = $ordini;

    }
    
    function viewdettaglioAction()
    {
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Db_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine, $this->_userSessionVal->idgroup);
        if(is_null($ordine)) 
        {
            // REDIRECT
            $this->redirect("gestione-cassa", "index");
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
        
        // GET PRODOTTI Ordinati
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($mooObj->getIdOrdine(), $this->_userSessionVal->idgroup);
        
        // Add Calcoli Decorator
        $ordCalcoli = new Model_Ordini_CalcoliDecorator($mooObj);
        $ordCalcoli->setIdgroup($this->_userSessionVal->idgroup);
        $ordCalcoli->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcoli = $ordCalcoli;
        
        $this->view->ordine = $mooObj;
    }
    
    function archiviaAction()
    {
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Db_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine, $this->_userSessionVal->idgroup);
        if(is_null($ordine)) 
        {
            // REDIRECT
            $this->redirect("gestione-cassa", "index");
        }
        // build Ordine
        $mooObj = new Model_Ordini_Ordine( new Model_AF_UserOrdineFactory() );
        // build & init DATI Ordine
        $mooObj->appendDati()->initDati_ByObject($ordine);
        // build & init STATE Ordine
        $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );

        // Verifico Stato
        if($mooObj->canArchiviaOrdine() )
        {
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

            // GET PRODUCTS LIST with Qta Ordered
            $ordCalcObj = new Model_Ordini_CalcoliDecorator($mooObj);
            $ordCalcObj->setIdgroup($this->_userSessionVal->idgroup);

            // SET PRODOTTI ORDINATI
            $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($mooObj->getIdOrdine(),$this->_userSessionVal->idgroup);
            $ordCalcObj->setProdottiOrdinati($listProdOrdered);
            
            // Check If some product ordered EXISTS
            $cassaObj = new Model_Db_Cassa();
            if($ordCalcObj->getProdottiUtenti() > 0) {
                // RIPARTIZIONE in CASSA e ARCHIVIO ORDINE
                $res = $cassaObj->closeOrdine($ordCalcObj, $this->_userSessionVal->idgroup);
            } else {
                // SOLO ARCHIVIA ORDINE
                $res = $cassaObj->closeOrderByIdordineAndIdgroup($idordine, $this->_userSessionVal->idgroup);
            }
            if($res) {
                $this->redirect("gestione-cassa", "index");
            }
        }
    }
    
    
    function viewsaldiAction()
    {
        $cassaObj = new Model_Db_Cassa();
        $saldi = $cassaObj->getSaldiGroup($this->_userSessionVal->idgroup);
        $this->view->saldi = $saldi;
    }
    
}
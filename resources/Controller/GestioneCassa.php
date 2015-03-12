<?php
/**
 * Description of GESTIONE CASSA
 *
 * @author gullo
 */
class Controller_GestioneCassa extends MyFw_Controller {

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
        // get&set come filter
        $start = is_null($this->getParam("start")) ? 0 : $this->getParam("start");
        $limit = is_null($this->getParam("limit")) ? 20 : $this->getParam("limit");
        
        $cassaModel = new Model_Db_Cassa();
        $movs = $cassaModel->getUltimiMovimenti($start, $limit);
        $movimenti = array();
        if(count($movs) > 0)
        {
            foreach ($movs as $movimento) {
                $movimenti[] = new Model_Cassa_Movimento($movimento);
            }
        }
        $this->view->movimenti = $movimenti;
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
        $ordine = $ordObj->getByIdOrdine($idordine);
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
                
        // GET PRODUCTS LIST with Qta Ordered
        $ordCalcObj = new Model_Ordini_Calcoli_Utenti($mooObj);
        
        // SET PRODOTTI ORDINATI
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($mooObj->getIdOrdine());
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
        $this->view->ordine = $mooObj;
    }
    
    function archiviaAction()
    {
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Db_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
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
        $stateOrdine =  Model_Ordini_State_OrderFactory::getOrder($ordine);
        if($stateOrdine->canContabile_ArchiviaOrdine() )
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
            $ordCalcObj = new Model_Ordini_Calcoli_Utenti($mooObj);

            // SET PRODOTTI ORDINATI
            $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($mooObj->getIdOrdine());
            $ordCalcObj->setProdottiOrdinati($listProdOrdered);
            
            // Check If some product ordered EXISTS
            if($ordCalcObj->getProdottiUtenti() > 0)
            {
                $cassaObj = new Model_Db_Cassa();
                $produttoriList = ((count($mooObj->getProduttoriList()) > 0) ? implode(", ", $mooObj->getProduttoriList()) : "--");
                foreach ($ordCalcObj->getProdottiUtenti() AS $iduser => $user)
                {
                    $importo = -1 * abs($ordCalcObj->getTotaleConExtraByIduser($iduser));
                    $values = array(
                        'iduser'    => $iduser,
                        'importo'   => $importo,
                        'data'      => date("Y-m-d H:i:s"),
                        'descrizione' => 'Chiusura Ordine ' . $produttoriList,
                        'idordine'  => $mooObj->getIdOrdine()
                    );
                    $cassaObj->addMovimentoOrdine($values);
                }
                
                // ARCHIVIO ORDINE
                $res = $mooObj->moveToNextState();
                if($res)
                {
                    $this->redirect("gestione-cassa", "index");
                }
            }
        }
    }
}
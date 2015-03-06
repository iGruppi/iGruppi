<?php

/**
 * Description of GestioneOrdini Controller
 * 
 * @author gullo
 */
class Controller_GestioneOrdini extends MyFw_Controller {
    
    private $_userSessionVal;
    private $_iduser;
    private $_idordine;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');

        // Try to GET Ordine if the user try to manage it
        if( $this->getFrontController()->getAction() != "index" && $this->getFrontController()->getAction() != "new")
        {
            $idordine = $this->getParam("idordine");
            if(is_null($idordine)) {
                $this->redirect("index", "error", array('code' => 404));
            }
            $this->_idordine = $idordine;
        }

        // Get updated if it is set
        $this->view->updated = $this->getParam("updated");        
        
    }
    
    function indexAction() {
        
        $filter = $this->getParam("filter");
        if(is_null($filter)) 
        {
            $filter = "PRI"; // DEFAULT value
        }
        $this->view->filter = $filter;
        
        $ordObj = new Model_Db_Ordini();
        $cObj = new Model_Db_Categorie();
        $listOrd = $ordObj->getOrdiniByIdIdgroup($this->_userSessionVal->idgroup, $filter);
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
    
    function newAction() {
        
        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/new");
        // remove useless fields
        $form->removeField("visibile");
        $form->removeField("costo_spedizione");
        $form->removeField("note_consegna");
        $form->removeField("condivisione");
        $form->removeField("iduser_ref");
        $form->removeField("idordine");
        
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {                
                // SAVE to DB
                $idordine = $this->getDB()->makeInsert("ordini", $form->getValues());
                
                // Add ALL prodotti ATTIVI by Default!
                $prodObj = new Model_Db_Prodotti();
                $prodotti = $prodObj->getProdottiByIdProduttore($this->_produttore->idproduttore, 'S');
                if(count($prodotti) > 0) {
                    $ordObj = new Model_Db_Ordini();
                    foreach($prodotti AS $prodotto) {
                        $arVal[] = array('idprodotto' => $prodotto->idprodotto, 'costo' => $prodotto->costo);
                    }
                    $ordObj->addProdottiToOrdine($idordine, $arVal);
                }
                
                $this->redirect("gestione-ordini", "dashboard", array("idordine" => $idordine, "updated" => true));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
        
        
        // Get Listini to create ordine
        $lObj = new Model_Db_Listini();
        $cObj = new Model_Db_Categorie();
        $listiniArray = $lObj->getListiniAvailableToCreateOrder($this->_userSessionVal->idgroup, $this->_iduser);
        $listini = array();
        if(!is_null($listiniArray)) {
            foreach ($listiniArray as $stdListino) {
                // creates Listino by Abstract Factory Model_AF_ListinoFactory
                $mllObj = new Model_Listini_Listino();
                $mllObj->appendDati()
                       ->appendGruppi()              
                       ->appendCategorie();
                // init Dati by stdClass
                $mllObj->initDati_ByObject($stdListino); 
                
                // set Categories in Listini object
                $categorie = $cObj->getCategoriesByIdListino( $mllObj->getIdListino() );
                // get CATEGORIE by array 
                $mllObj->initCategorie_ByObject($categorie);
                
                // set GROUPS in Listino
                $mllObj->initGruppi_ByObject( $lObj->getGroupsByIdlistino( $mllObj->getIdListino() ) );
                $mllObj->setMyIdGroup($this->_userSessionVal->idgroup);
                
                // add Listino to array
                array_push($listini, $mllObj);
            }
        }
        $this->view->listini = $listini;
        
    }
    
/******************
 *  ACTIONs TO MANAGE a SINGLE ORDER
 * 
 */    
    
    private function _buildOrdine(Model_AF_AbstractFactory $factoryClass)
    {
        $ordObj = new Model_Db_Ordini();
        $ordine = $ordObj->getByIdOrdine($this->_idordine);
        if(!is_null($ordine)) 
        {
            // build Ordine
            $mooObj = new Model_Ordini_Ordine( $factoryClass );
            // build & init DATI Ordine
            $mooObj->appendDati()->initDati_ByObject($ordine);
            // build & init STATE Ordine
            $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );

            // build & init Gruppi
//            Zend_Debug::dump($ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()));die;
            $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
            $mooObj->setMyIdGroup($this->_userSessionVal->idgroup);
            
            // creo elenco prodotti
            $prodottiModel = new Model_Db_Prodotti();
            $listProd = $prodottiModel->getProdottiByIdOrdine($this->_idordine);

            // build & init CATEGORIE
            $mooObj->appendCategorie()->initCategorie_ByObject($listProd);

            // build & init PRODOTTI
            $mooObj->appendProdotti()->initProdotti_ByObject($listProd);
            
            // set Ordine in the View by default
            $this->view->ordine = $mooObj;
            
            return $mooObj;
        }
        return null;
    }
    
    
    
    function dashboardAction()
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        
        // Get Log Variazioni
        $sth = $this->getDB()->prepare("SELECT * FROM ordini_variazioni WHERE idordine= :idordine ORDER BY data DESC");
        $sth->execute(array('idordine' => $ordine->getIdOrdine()));
        $this->view->logs = $sth->fetchAll(PDO::FETCH_OBJ);
    }
    
    function editAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        
        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/edit/idordine/".$ordine->getIdOrdine());
        $form->setValue("idordine", $ordine->getIdOrdine());
        
        /**
         * TODO: Disabilita campo Condivisione se l'ordine è già avviato!
         * 
            $form->getField("condivisione")->setAttribute("disabled", true);
         * 
         */
        if(!$ordine->canManageDate())
        {
            $form->removeField("data_inizio");
            $form->removeField("data_fine");
        }
        if(!$ordine->canManageUsersRef()) {
            $form->removeField("iduser_ref");
        }
        

        // get elenco All Groups to fill checkboxes in the view (Condivisione)
        $grObj = new Model_Db_Groups();
        $this->view->groups = $groups = $grObj->getAll();
        
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {    
                if($ordine->canManageDate()) {
                    $ordine->setDataInizio($form->getValue("data_inizio"));
                    $ordine->setDataFine($form->getValue("data_fine"));
                }
                if($ordine->canManageCondivisione()) {
                    $ordine->setCondivisione($form->getValue("condivisione"));
                    // Save GROUPS
                    $groupsToShare = isset($fv["groups"]) ? $fv["groups"] : array();
                    $ordine->resetGroups($form->getValue("condivisione"), $groupsToShare);                
                }
                if($ordine->canManageUsersRef()) {
                    $ordine->getMyGroup()->setRefIdUser($form->getValue("iduser_ref"));
                }
                // Every group can set this data personalized
                $ordine->getMyGroup()->setCostoSpedizione($form->getValue("costo_spedizione"));
                $ordine->getMyGroup()->setNoteConsegna($form->getValue("note_consegna"));
                $ordine->getMyGroup()->setVisibile($form->getValue("visibile"));
                
                // SAVE ALL DATA CHANGED TO DB & REDIRECT
                $resSaveGruppi = $ordine->saveToDB_Gruppi();
                $resSaveDati = $ordine->saveToDB_Dati();
                if($resSaveDati && $resSaveGruppi) {
                    $this->redirect("gestione-ordini", "dashboard", array("idordine" => $ordine->getIdOrdine(), "updated" => true));
                }
            }
        } else {
            $form->setValues($ordine->getDatiValues());
            // Set this data only if CAN MANAGE DATE
            if($ordine->canManageDate()) {
                $form->setValue("data_inizio", $ordine->getDataInizio(MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW));
                $form->setValue("data_fine", $ordine->getDataFine(MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW));
            }
            // Set this data only if CAN MANAGE USER-REF
            if($ordine->canManageUsersRef()) {
                $form->setValue("iduser_ref", $ordine->getMyGroup()->getRefIdUser());
            }
            $form->setValue("costo_spedizione", $ordine->getMyGroup()->getCostoSpedizione());
            $form->setValue("note_consegna", $ordine->getMyGroup()->getNoteConsegna());
            $form->setValue("visibile", $ordine->getMyGroup()->getVisibile()->getString());
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    function prodottiAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
    }
    
    function updateprodottoAction()
    {
        Zend_Registry::get("layout")->disableDisplay();
        
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        
        // get param IdProdotto and check it if exists!
        $idprodotto = $this->getParam("idprodotto");
        
        $result = array('res' => false);
        
        // get Prodotto Ordine valeus from DB
        $prodotto = $ordine->getProdottoById($idprodotto);
        if(!is_null($prodotto))
        {
            $field = $this->getParam("field");
            $value = $this->getParam("value");
            switch ($field) {
                case "disponibile_ordine":
                    $prodotto->setDisponibileOrdine($value);
                    break;
                case "costo_ordine":
                    $prodotto->setCostoOrdine($value);
                    break;
                case "offerta_ordine":
                    $prodotto->setOffertaOrdine($value);
                    break;
                case "sconto_ordine":
                    $prodotto->setScontoOrdine($value);
                    break;
            }
            $res = $prodotto->saveToDB_Prodotto();
            if($res) {
                $result = array('res' => true);
                // LOG VARIAZIONE DATO 
                Model_Ordini_Logger::LogVariazioneProdottoByField($ordine, $prodotto, $this->getParam("field"));
            }
        }
        
        echo json_encode($result);
    }
    
    function qtaordineAction()
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        // GET PRODUCTS LIST with Qta Ordered
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($ordine->getIdOrdine());
        $ordCalcObj = new Model_Ordini_Calcoli_Utenti();
        // SET ORDINE e PRODOTTI
        $ordCalcObj->setOrdine($ordine);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function getformqtaAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        $this->view->iduser = $iduser = $this->getParam("iduser");
        $this->view->idprodotto = $idprodotto = $this->getParam("idprodotto");
        $this->view->idordine = $idordine = $this->getParam("idordine");
        // GET Prodotto ordinato
        $pObj = $ordine->getProdottoById($idprodotto);
        if(!is_null($pObj)) 
        {
            $this->view->pObj = $pObj;
            echo json_encode(array('res' => true, 'myTpl' => $this->view->fetch('gestioneordini/qtaordine-row.form.tpl.php')));
        } else {
            echo json_encode(array('res' => false));
        }
    }
    
    function changeqtaAction()
    {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        if($this->getRequest()->isPost()) {
            // get Post values
            $fv = $this->getRequest()->getPost();
            $idordine = $fv["idordine"];
            $iduser = $fv["iduser"];
            $idprodotto = $fv["idprodotto"];
            $qta_reale = $fv["qta_reale"];
            
            $sth = $this->getDB()->prepare("UPDATE ordini_user_prodotti SET qta_reale= :qta_reale, data_ins=NOW() WHERE iduser= :iduser AND idprodotto= :idprodotto AND idordine= :idordine");
            // UPDATE product selected
            $fields = array('iduser' => $iduser, 'idprodotto' => $idprodotto, 'idordine' => $idordine, 'qta_reale' => $qta_reale);
            $rsth = $sth->execute($fields);
            if($rsth) 
            {
                $ordModel = new Model_Db_Ordini();
                $prodotti = $ordModel->getProdottiOrdinatiByIdordine($idordine);
                if(is_array($prodotti) && count($prodotti) > 0)
                {
                    $ordCalcObj = new Model_Ordini_Calcoli_Utenti();
                    $ordCalcObj->setOrdObj($ordine);
                    $ordCalcObj->setProdotti($prodotti);
                    $prodObj = $ordCalcObj->getProdottiByIduser($iduser);
                    $newTotale = 0;
                    if( isset($prodObj[$idprodotto]) ) {
                        $pObj = $prodObj[$idprodotto];
                        $newTotale = $pObj->getTotale();
                    }
                    echo json_encode(array('res' => true, 'newTotale' => $newTotale, 'grandTotal' => $ordCalcObj->getTotaleConSpedizioneByIduser($iduser)));
                    exit;
                }
            }
        }
        echo json_encode(array('res' => false));
    }
    
    function newprodformAction() 
    {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();

        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
                
        $this->view->iduser = $iduser = $this->getParam("iduser");
        $this->view->idordine = $idordine = $this->getParam("idordine");
        
        // GET All products available
        $listProd = $ordine->getProdotti();
        $arRes = array();
        if(is_array($listProd) && count($listProd) > 0) {
            foreach($listProd AS $prodotto) 
            {
                $arRes[] = array('id' => $prodotto->getIdProdotto(), 'label' => $prodotto->getDescrizioneListino(), 'category' => $prodotto->getSubcategoria());
            }
        }
        $this->view->arRes = json_encode($arRes);
        
        echo json_encode(array('res' => true, 'myTpl' => $this->view->fetch('gestioneordini/qtaordine-newprod.form.tpl.php')));

    }
    
    function newprodsaveAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        
        if($this->getRequest()->isPost()) {
            // get Post values
            $fv = $this->getRequest()->getPost();
            $idordine = $fv["idordine"];
            $iduser = $fv["iduser"];
            $idprodotto = $fv["idprodotto"];
        
            $ordObj = new Model_Db_Ordini();
            $added = $ordObj->addQtaProdottoForOrdine($idordine, $iduser, $idprodotto);
            if($added) {
                $prodotti = $ordObj->getProdottiOrdinatiByIdordine($idordine);
                if(is_array($prodotti) && count($prodotti) > 0) {
                    $ordCalcObj = new Model_Ordini_Calcoli_Utenti();
                    $ordCalcObj->setOrdObj($ordine);
                    $ordCalcObj->setProdotti($prodotti);
                    $prodObj = $ordCalcObj->getProdottiByIduser($iduser);
                    if( isset($prodObj[$idprodotto]) ) {
                        $this->view->pObj = $prodObj[$idprodotto];
                        $this->view->iduser = $iduser;
                        $this->view->idordine = $idordine;
                        $this->view->idprodotto = $idprodotto;
                        echo json_encode(array('res' => true, 
                                   'grandTotal' => $ordCalcObj->getTotaleConSpedizioneByIduser($iduser), 
                                   'myTpl' => $this->view->fetch('gestioneordini/qtaordine-row.tpl.php')));
                        exit();
                    }
                }
            }
        }
        
        // Some error...
        echo json_encode(array('res' => false));
    }
    
    function dettaglioAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        // get View by Tipo
        $tipo = $this->getParam("tipo");
        if(is_null($tipo)) 
        {
            $tipo = "totali";
        }
        
        // GET PRODUCTS LIST with Qta Ordered
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($ordine->getIdOrdine());
        //Zend_Debug::dump( $listProdOrdered ); die;
        $this->view->tipo = $tipo;
        switch ($tipo) 
        {
            case "totali":
                $ordCalcObj = new Model_Ordini_Calcoli_Totali();
                break;

            case "utenti":
                $ordCalcObj = new Model_Ordini_Calcoli_Utenti();
                break;

            case "prodotti":
                $ordCalcObj = new Model_Ordini_Calcoli_Prodotti();
                break;
        }
        // SET ORDINE e PRODOTTI
        $ordCalcObj->setOrdine($ordine);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function inviaAction() {
        // TODO: Invia email...
    }
    
    function movestatusAction() {
        
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        // MOVE order to the newStatus
        $flagMover = $this->getParam("flagMover");
        if($flagMover == "next") 
        {
            $res = $ordine->moveToNextState();
        } else {
            $res = $ordine->moveToPrevState();
        }
        $result = array('res' => $res);
        if($res) {
            // GET new ORDER data
            $orderObj = new Model_Db_Ordini();
            $ordine = $orderObj->getByIdOrdine($ordine->getIdOrdine());
            if($ordine) {
                // init Dati Ordine
                // build Ordine
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                $mooObj->appendDati();
                $mooObj->appendCategorie();
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );
                // init Dati Ordine
                $mooObj->initDati_ByObject($ordine);
                $this->view->ordine = $mooObj;
                $myTpl = $this->view->fetch("gestioneordini/gestione-header.tpl.php");
                $result = array('res' => true, 'myTpl' => $myTpl);
            }
        }
        echo json_encode($result);
    }
    
    
}
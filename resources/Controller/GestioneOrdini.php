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
    
    function indexAction() 
    {
        $filter = $this->getParam("filter");
        if(is_null($filter)) 
        {
            $filter = "Aperto"; // DEFAULT value
        }
        $this->view->filter = $filter;
        
        $ordObj = new Model_Db_Ordini();
        $cObj = new Model_Db_Categorie();
        $listOrd = $ordObj->getOrdiniByIdIdgroup($this->_userSessionVal->idgroup);
        $ordini = array();
        $counterOrdiniStati = array();
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
                if($mooObj->getStateName() == $filter && 
                   $mooObj->canManageOrdine()
                ) {
                    $ordini[] = $mooObj;
                }
                // add in counter for States
                if(!isset($counterOrdiniStati[$mooObj->getStateName()]) ) {
                    $counterOrdiniStati[$mooObj->getStateName()] = 1;
                } else {
                    $counterOrdiniStati[$mooObj->getStateName()]++;
                }
            }
        }
        $this->view->counterOrdiniStati = $counterOrdiniStati;
        $this->view->ordini = $ordini;
        // set Model_Produttori_Permessi Object in View
        $this->view->permsProduttori = $this->_userSessionVal->permsProduttori;
    }
    
    function newAction() {
        
        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/new");
        // remove useless fields
        $form->removeField("visibile");
        $form->removeField("note_consegna");
        $form->removeField("condivisione");
        $form->removeField("iduser_incaricato");
        $form->removeField("idordine");
        // Specific error for LISTINI selection
        $this->view->errorListino = false;

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {       
                // Check listino selection
                $listini = isset($fv["listini"]) ? $fv["listini"] : null;
                if(is_array($listini) && count($listini) > 0)
                {
                    // BUILD a new Listino
                    $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                    $mooObj->appendDati();
                    $mooObj->appendGruppi();

                    // SAVE ORDINE to DB
                    $mooObj->setDescrizione($form->getValue("descrizione"));
                    $mooObj->setDataInizio($form->getValue("data_inizio"));
                    $mooObj->setDataFine($form->getValue("data_fine"));
                    $mooObj->setCondivisione("PRI"); // Default is Private

                    if( $mooObj->saveToDB_Dati() ) 
                    {
                        $idordine = $mooObj->getIdOrdine();
                        // create a NEW group
                        $group = new stdClass();
                        $group->id = $idordine;
                        $group->idgroup_master = $this->_userSessionVal->idgroup;
                        $group->idgroup_slave = $this->_userSessionVal->idgroup;
                        $group->iduser_incaricato = $this->_iduser;
                        // add my group
                        $mooObj->addGroup($group);
                        $resSave = $mooObj->saveToDB_Gruppi();                
                        if($resSave)
                        {

                            // Add the products of the selected LISTINI to ORDINI
                            $ordineObj = new Model_Db_Ordini();
                            $res = $ordineObj->createOrdiniByListini($idordine, $listini);
                            if($res) {
                                $this->redirect("gestione-ordini", "dashboard", array("idordine" => $idordine, "updated" => true));
                            }
                        }
                    }
                } else {
                    $this->view->errorListino = true;
                }
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
                
                // CHECK VALIDITA' e VISIBILITA'
                if($mllObj->getValidita()->isValido() &&
                   $mllObj->getVisibile()->getBool() ) 
                {
                    array_push($listini, $mllObj);
                }
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
         * DISABLE some fields IF cannot manage them
         */
        if(!$ordine->canManageDescrizione()) {
            $form->getField("descrizione")->setDisabled();
        }
        if(!$ordine->canUpdateVisibile()) {
            $form->getField("visibile")->setDisabled();
        }
        if(!$ordine->canManageDate())
        {
            $form->getField("data_inizio")->setDisabled();
            $form->getField("data_fine")->setDisabled();
        }
        if(!$ordine->canManageCondivisione()) {
            $form->getField("condivisione")->setDisabled();
            $form->getField("groups")->setDisabled();
        }
        if(!$ordine->canManageIncaricato()) {
            $form->getField("iduser_incaricato")->setDisabled();
        }

        // check POST and valid data
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {    
                if($ordine->canManageDescrizione()) {
                    $ordine->setDescrizione($form->getValue("descrizione"));
                }
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
                if($ordine->canManageIncaricato()) {
                    $iduser_incaricato = ($form->getValue("iduser_incaricato") > 0) ? $form->getValue("iduser_incaricato") : NULL;
                    $ordine->getMyGroup()->setIncaricato($iduser_incaricato);
                }
                if($ordine->canUpdateVisibile()) {
                    $ordine->getMyGroup()->setVisibile($form->getValue("visibile"));
                }
                // Every group can set this data personalized
                $ordine->getMyGroup()->setNoteConsegna($form->getValue("note_consegna"));
                
                // SAVE ALL DATA CHANGED TO DB & REDIRECT
                $resSaveGruppi = $ordine->saveToDB_Gruppi();
                $resSaveDati = $ordine->saveToDB_Dati();
                if($resSaveDati && $resSaveGruppi) {
                    $this->redirect("gestione-ordini", "dashboard", array("idordine" => $ordine->getIdOrdine(), "updated" => true));
                }
            }
        } else {
            $form->setValues($ordine->getDatiValues());
            $form->setValue("data_inizio", $ordine->getDataInizio(MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW));
            $form->setValue("data_fine", $ordine->getDataFine(MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW));
            $form->setValue("iduser_incaricato", $ordine->getMyGroup()->getIdUser_Incaricato());
            $form->setValue("note_consegna", $ordine->getMyGroup()->getNoteConsegna());
            $form->setValue("visibile", $ordine->getVisibile()->getString());
            $form->setValue("groups", $ordine->getAllIdgroups());
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    function speseextraAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        
        if($this->getRequest()->isPost()) {
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if(isset($fv["extra"]) && is_array($fv["extra"]))
            {
                if(count($fv["extra"]) > 0) 
                {
                    $ordine->getSpeseExtra()->resetSpese();
                    foreach($fv["extra"] AS $extraVal)
                    {
                        $ordine->getSpeseExtra()->addSpesa($extraVal["descrizione"], $extraVal["costo"], $extraVal["tipo"]);
                    }
                    // save to db
                    $ordine->saveToDB_Gruppi();
                }
            }
        }
        $this->view->ordine = $ordine;
    }
    
    
    
    function prodottiAction() 
    {
        // build Ordine
        $this->_buildOrdine( new Model_AF_OrdineFactory() );
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
        $ordCalcObj = new Model_Ordini_CalcoliDecorator($ordine);
        // SET PRODOTTI ORDINATI
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordineAndIdgroup($ordine->getIdOrdine(),$this->_userSessionVal->idgroup);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function changeqtaAction()
    {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        // get Params values
        $iduser = $this->getParam("iduser");
        $idprodotto = $this->getParam("idprodotto");
        $idlistino = $this->getParam("idlistino");
        $field = $this->getParam("field");
        $value = $this->getParam("value");
            
        $sth = $this->getDB()->prepare("UPDATE ordini_user_prodotti SET $field= :$field, data_ins=NOW() WHERE iduser= :iduser AND idordine= :idordine AND idlistino= :idlistino  AND idprodotto= :idprodotto");
        // UPDATE product selected
        $fields = array('iduser' => $iduser, 'idordine' => $this->_idordine, 'idprodotto' => $idprodotto, 'idlistino' => $idlistino, $field => $value);
        $rsth = $sth->execute($fields);
        echo json_encode(array('res' => $rsth));
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
    
    function newprodsaveAction() 
    {
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
                $prodotti = $ordObj->getProdottiOrdinatiByIdordineAndIdgroup($idordine,$this->_userSessionVal->idgroup);
                if(is_array($prodotti) && count($prodotti) > 0) {
                    $ordCalcObj = new Model_Ordini_CalcoliDecorator();
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
        $this->view->tipo = $tipo;
        
        // add CALCOLI DECORATOR
        $ordCalcObj = new Model_Ordini_CalcoliDecorator($ordine);

        // SET PRODOTTI ORDINATI in DECORATOR
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordineAndIdgroup($ordine->getIdOrdine(),$this->_userSessionVal->idgroup);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        
        //Zend_Debug::dump($ordCalcObj);die;
        
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
        echo json_encode($res);
    }
    
    
}
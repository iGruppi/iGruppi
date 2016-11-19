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
                // add Ordine to the list checking permission and filters
                if($mooObj->canManageOrdine()) {
                    // ADD to array to view
                    if($mooObj->getStateName() == $filter) {
                        
                        $ordini[] = $mooObj;
                    }
                    // ADD in counter for States
                    if(!isset($counterOrdiniStati[$mooObj->getStateName()]) ) {
                        $counterOrdiniStati[$mooObj->getStateName()] = 1;
                    } else {
                        $counterOrdiniStati[$mooObj->getStateName()]++;
                    }
                }
            }
        }
        $this->view->counterOrdiniStati = $counterOrdiniStati;
        $this->view->ordini = $ordini;
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
        $ordine = $ordObj->getByIdOrdine($this->_idordine, $this->_userSessionVal->idgroup);
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
        
        // elenco users per Gruppo
        $uModel = new Model_Db_Users();
        $this->view->arrayUsers = $uModel->getUsersByIdGroup($this->_userSessionVal->idgroup);
        
        // elenco prodotti ordinabili
        $pModel = new Model_Db_Prodotti();
        $this->view->arrayProdotti = $pModel->getProdottiByIdOrdine($ordine->getIdOrdine());
        $arrayProdotti = array();
        foreach($this->view->arrayProdotti AS $prodotto)
        {
            $arrayProdotti[$prodotto->categoria_sub][$prodotto->idprodotto] = $prodotto;
        }
        
        $this->view->arrayProdotti = $arrayProdotti;
        // GET PRODUCTS LIST with Qta Ordered
        $ordCalcObj = new Model_Ordini_CalcoliDecorator($ordine);
        $ordCalcObj->setIdgroup($this->_userSessionVal->idgroup);
        // SET PRODOTTI ORDINATI
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($ordine->getIdOrdine(),$this->_userSessionVal->idgroup);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function changeqtaAction()
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
            // get Params values
            $iduser = $this->getParam("iduser");
            $idlistino = $this->getParam("idlistino");
            $value = $this->getParam("value");
            $whois = $this->getParam("whois");

            $sth = $this->getDB()->prepare("UPDATE ordini_user_prodotti SET qta_reale= :qta_reale, data_ins=NOW() WHERE iduser= :iduser AND idordine= :idordine AND idlistino= :idlistino  AND idprodotto= :idprodotto");
            // UPDATE product selected
            $fields = array('iduser' => $iduser, 'idordine' => $this->_idordine, 'idprodotto' => $idprodotto, 'idlistino' => $idlistino, 'qta_reale' => $value);
            $rsth = $sth->execute($fields);
            if($rsth) {
                $result = array('res' => true);
                // LOG VARIAZIONE DATO 
                Model_Ordini_Logger::LogVariazioneQtaUser($ordine, $prodotto, $iduser, $value, $whois);
            }
        }
        
        echo json_encode($result);
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
                $arRes[] = array('id' => $prodotto->getIdProdotto(), 'label' => $prodotto->getDescrizioneAnagrafica(), 'category' => $prodotto->getSubcategoria());
            }
        }
        $this->view->arRes = json_encode($arRes);
        
        echo json_encode(array('res' => true, 'myTpl' => $this->view->fetch('gestioneordini/qtaordine-newprod.form.tpl.php')));

    }
    
    function newprodsaveAction() 
    {
        Zend_Registry::get("layout")->disableDisplay();

        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        
        if($this->getRequest()->isPost()) {
            // get Post values
            $fv = $this->getRequest()->getPost();
            
            // SET params
            $prod_list = json_decode($fv["idprodotto"]);
            $idprodotto = $prod_list->idprodotto;
            $idlistino = $prod_list->idlistino;
            $idordine = $this->getParam("idordine");
            $iduser = $fv["iduser"];
            $qta = $fv["qta"];
                
            // get Prodotto Ordine valeus from DB
            $prodotto = $ordine->getProdottoById($idprodotto);
            if(!is_null($prodotto))
            {
                $ordObj = new Model_Db_Ordini();
                $rsth = $ordObj->setQtaProdottoForOrdine($idordine, $idlistino, $idprodotto, $iduser, $qta);
                if($rsth) {
                    // LOG VARIAZIONE DATO 
                    Model_Ordini_Logger::LogAggiuntoProdottoUser($ordine, $prodotto, $iduser, $qta, "Incaricato");
                }
            }
            
            $this->redirect("gestione-ordini", "qtaordine", array('idordine' => $idordine));
        }
    }
    
    function dettaglioAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        // GET View by Tipo
        $tipo = $this->getParam("tipo");
        if(is_null($tipo)) {
            $tipo = "totali";
        }
        $this->view->tipo = $tipo;
        
        // GET idgroup (only for Supervisore Ordine)
        $idgroup = $this->_userSessionVal->idgroup; // DEFAULT idgroup of the user
        if($ordine->canViewMultigruppoFunctions()) { 
            if( !is_null($this->getParam("idgroup"))) {
                $idgroup = $this->getParam("idgroup");
            }
            // SET idgroup in Ordine
            $ordine->setMyIdGroup($idgroup);
        }
        $this->view->idgroup = $idgroup;
        
        // init Model DB Ordini
        $ordObj = new Model_Db_Ordini();

        // GET elenco GRUPPI che hanno ordinato
        $groups = $ordObj->getGroupsWithAlmostOneProductOrderedByIdOrdine($ordine->getIdOrdine());
        $this->view->groups = $groups;
//        Zend_Debug::dump($groups);die;
        
        // add CALCOLI DECORATOR
        $ordCalcObj = new Model_Ordini_CalcoliDecorator($ordine);
        $ordCalcObj->setIdgroup($idgroup);

        // SET PRODOTTI ORDINATI in DECORATOR
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($ordine->getIdOrdine(), $idgroup);
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        
        //Zend_Debug::dump($ordCalcObj);die;
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function dettagliomgAction() 
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        // CHECK if can access this area
        if(!$ordine->canViewMultigruppoFunctions()) { 
            $this->redirect("gestione-ordini", "dashboard", array("idordine" => $ordine->getIdOrdine()));
        }
        
        // GET View by Tipo
        $tipo = $this->getParam("tipo");
        if(is_null($tipo)) {
            $tipo = "totali";
        }
        $this->view->tipo = $tipo;
        
        // add CALCOLI DECORATOR
        $ordCalcObj = new Model_Ordini_CalcoliMultigruppoDecorator($ordine);

        // SET PRODOTTI ORDINATI in DECORATOR
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($ordine->getIdOrdine());
        $ordCalcObj->setProdottiOrdinati($listProdOrdered);
        
//        Zend_Debug::dump($ordCalcObj->getProdottiUtenti());die;
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function inviaAction() 
    {
        Zend_Registry::get("layout")->disableDisplay();
                
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_UserOrdineFactory() );
        
        $return = array('res' => false, 'msg' => 'ERROR!');
        
        // check data from POST
        if($this->getRequest()->isPost()) {
            // get Post values
            $fv = $this->getRequest()->getPost();
            $recipient = $fv["recipient"];
            $groups = $fv["groups"];
            $oggetto = $fv["oggetto"];
            $messaggio = $fv["messaggio"];
            
            // get email addresses
            $mdNotifiche = new Model_Db_Notifiche();
            switch ($recipient) {
                case "Amministratori":  $emails = $mdNotifiche->getEmails_Admins($groups); break;
                case "Incaricati":      $emails = $mdNotifiche->getEmails_Incaricati($ordine->getIdOrdine(), $groups); break;
                case "Utenti":          $emails = $mdNotifiche->getEmails_UsersOrder($ordine->getIdOrdine(), $groups); break;
            }
            
            // GET user identity
            $userData = Zend_Auth::getInstance()->getStorage()->read();
            
            // PREPARE EMAIL TO GROUP LIST
            $mail = new MyFw_Mail();
            $mail->clearFrom()->setFrom($userData->email, $userData->nome . " " . $userData->cognome);
            $mail->setSubject("[".$ordine->getDescrizione()." #".$ordine->getIdOrdine()."] - " . $oggetto);
            //$mail->setViewParam("ordine", $ordine);
            $mail->setViewParam("messaggio", $messaggio);
            
            // check "Send to me" to SET the correct [TO]
            if(isset($fv["send_to_me"]) && $fv["send_to_me"] == 1) {
                $mail->addTo($userData->email);
            } else {
                $mail->setDefaultTo();
            }
            
            // GET USERS LIST
            if(count($emails) > 0)
            {
                foreach($emails AS $email)
                {
                    $mail->addBcc($email);
                }
            }

            // SEND IT...
            $res = $mail->sendHtmlTemplate("notifica_ordine.tpl.php");
            $return = array('res' => $res, 'msg' => 'OK!');
        }
        
        echo json_encode($return);
    }
    
    function movestatusAction() 
    {
        
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
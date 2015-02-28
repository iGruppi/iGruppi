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
        
        $ordObj = new Model_Db_Ordini();
        $cObj = new Model_Db_Categorie();
        $listOrd = $ordObj->getAllByIdUserRef($this->_iduser);
        $ordini = array();
        if(count($listOrd) > 0) {
            foreach($listOrd AS $ordine) {
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                $mooObj->appendDati()->initDati_ByObject($ordine);
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );
                
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
        $form->setAction("/gestione-ordini/new/idproduttore/".$this->_produttore->idproduttore);
        $form->setValue("idgroup", $this->_userSessionVal->idgroup);
        $form->setValue("idproduttore", $this->_produttore->idproduttore);
        // remove useless fields
        $form->removeField("costo_spedizione");
        $form->removeField("archiviato");
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
        $sth = $this->getDB()->prepare("SELECT * FROM ordini_variazioni WHERE idordine= :idordine");
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
        $form->removeField("condivisione");

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {    
                // ADD Ordine in stato NEW
                $this->getDB()->makeUpdate("ordini", "idordine", $form->getValues() );
                // REDIRECT
                $this->redirect("gestione-ordini", "dashboard", array("idordine" => $ordine->getIdOrdine(), "updated" => true));
            }
        } else {
            $form->setValues($ordine->getDatiValues());
            $form->setValue("data_inizio", $ordine->getDataInizio());
            $form->setValue("data_fine", $ordine->getDataFine());
//            Zend_Debug::dump($form->getValues());die;
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    function sharingAction()
    {
        // build Ordine
        $ordine = $this->_buildOrdine( new Model_AF_OrdineFactory() );
        // add Gruppi
        $lObj = new Model_Db_Ordini();
        $ordine->appendGruppi()->initGruppi_ByObject( $lObj->getGroupsByIdOrdine( $ordine->getIdOrdine()));
        $ordine->setMyIdGroup($this->_userSessionVal->idgroup);
        
        // get elenco All Groups
        $grObj = new Model_Db_Groups();
        $this->view->groups = $groups = $grObj->getAll();
        
        // init Listino form
        $form = new Form_Ordini();
        $form->setAction("/getsione-ordini/sharing/idordine/" . $ordine->getIdOrdine());
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
        
        // get Prodotto Ordine valeus from DB
        $prodotto = $ordine->getProdottoById($idprodotto);
        if(is_null($prodotto))
        {
            $result = array('res' => false);
        }
        
        // build data
        $arValues = array(
            'idordine'  => $ordine->getIdOrdine(),
            'idlistino' => $this->getParam("idlistino"),
            'idprodotto' => $idprodotto,
            $this->getParam("field") => $this->getParam("value")
        );
        
        // UPDATE Ordine in stato NEW
        $res = $this->getDB()->makeUpdate("ordini_prodotti", array("idordine","idlistino","idprodotto"), $arValues );
        if($res) {
            $result = array('res' => true);
            // LOG VARIAZIONE DATO - DA SISTEMARE: dà errore -> SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails
//            Model_Ordini_Logger::LogByField($this->getParam("field"), $arValues);
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
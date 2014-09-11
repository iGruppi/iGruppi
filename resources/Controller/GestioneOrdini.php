<?php

/**
 * Description of GestioneOrdini Controller
 * 
 * @author gullo
 */
class Controller_GestioneOrdini extends MyFw_Controller {
    
    private $_userSessionVal;
    private $_iduser;
    private $_produttore;
    private $_ordine;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
        
        // Try to GET Produttore
        $idproduttore = $this->getParam("idproduttore");
        if(is_null($idproduttore)) {
            // Try to GET Ordine
            $idordine = $this->getParam("idordine");
            if(!is_null($idordine)) {
                $ordObj = new Model_Db_Ordini();
                $ordine = $ordObj->getByIdOrdine($idordine);
                if(!is_null($ordine)) {
                    $this->_ordine = $this->view->ordine = $ordine;
                    $this->view->statusObj = new Model_Ordini_Status($ordine);                    
                    $idproduttore = $ordine->idproduttore;
                }
            }
        }
        if(is_null($idproduttore)) {
            $this->redirect("index", "error", array('code' => 404));
        }
        $produttoreObj = new Model_Db_Produttori();
        $produttore = $produttoreObj->getProduttoreById($idproduttore);
        $this->_produttore = $this->view->produttore = $produttore;
        
        // check REFERENTE, controllo per i furbi (non Referenti)
        if(!$this->_userSessionVal->refObject->is_Referente($idproduttore)) {
            $this->redirect("index", "error", array('code' => 404));
        }
        
        // Get updated if it is set
        $this->view->updated = $this->getParam("updated");        
        
    }
    
    function indexAction() {
        
        $ordObj = new Model_Db_Ordini();
        $listOrd = $ordObj->getAllByIdUserRef($this->_iduser);
        if(count($listOrd) > 0) {
            foreach($listOrd AS &$ordine) {
                $ordine->statusObj = new Model_Ordini_Status($ordine);
            }
        }
        $this->view->list = $listOrd;
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
    function dashboardAction()
    {
        // Get Log Variazioni
        $sth = $this->getDB()->prepare("SELECT * FROM ordini_variazioni WHERE idordine= :idordine");
        $sth->execute(array('idordine' => $this->_ordine->idordine));
        $this->view->logs = $sth->fetchAll(PDO::FETCH_OBJ);
        
        
    }
    
    function editAction() {

        $form = new Form_Ordini();
        $form->setAction("/gestione-ordini/edit/idordine/".$this->_ordine->idordine);
        $form->setValue("idgroup", $this->_userSessionVal->idgroup);
        $form->setValue("idproduttore", $this->_produttore->idproduttore);
        $form->setValue("idordine", $this->_ordine->idordine);
        // remove useless fields
        $form->removeField("archiviato");

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {    
                // ADD Ordine in stato NEW
                $this->getDB()->makeUpdate("ordini", "idordine", $form->getValues() );
                // REDIRECT
                $this->redirect("gestione-ordini", "dashboard", array("idordine" => $this->_ordine->idordine, "updated" => true));
            }
        } else {
            // build array values for form
            $ordVal = clone $this->_ordine;
            $form->setValues($ordVal);
            $form->setValue("data_inizio", MyFw_Form_Filters_Date::filter($ordVal->data_inizio, array('date' => array( 'format' => MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW))));
            $form->setValue("data_fine", MyFw_Form_Filters_Date::filter($ordVal->data_fine, array('date' => array( 'format' => MyFw_Form_Filters_Date::_MYFORMAT_DATETIME_VIEW))));
//            Zend_Debug::dump($form->getValues());die;
        }
        
        // set Form in the View
        $this->view->form = $form;
    }

    function prodottiAction() {
        
        $ordObj = new Model_Db_Ordini();
        
        // SAVE FORM
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            
            $prodotti = isset($fv["prodotti"]) ? $fv["prodotti"] : array();
            if(count($prodotti) > 0) {
                // UPDATE products
                foreach ($prodotti as $idprodotto => &$val) {
                    $val["idprodotto"] = $idprodotto;
                    
                    // LOG VARIAZIONE PREZZO PRODOTTO
                    if($val["costo"] != $val["co"] )
                    {
                        Model_Ordini_Logger::LogVariazionePrezzo($this->_ordine->idordine, $idprodotto, $val["co"], $val["costo"]);
                    }
                    // remove "co" field
                    unset($val["co"]);
                }
                $updated = $ordObj->updateProdottiForOrdine($this->_ordine->idordine, $prodotti);
                if($updated)
                {
                    // REDIRECT
                    $this->redirect("gestione-ordini", "prodotti", array("idordine" => $this->_ordine->idordine, "updated" => true));
                }
            }
        }
        
        // Check for UPDATED flag
        if($this->view->updated)
        {
            $this->view->updated_msg = "La lista dei prodotti per quest'ordine è stata aggiornata con <strong>successo</strong>!";
        }

        // creo elenco prodotti (aggiornato dopo eventuale POST)
        $listProd = $ordObj->getProdottiByIdOrdine($this->_ordine->idordine);
        $listProdObj = array();
        if(count($listProd) > 0)
        {
            foreach ($listProd as $value)
            {
                $listProdObj[$value->idprodotto] = new Model_Ordini_Prodotto($value);
            }
        }
        $this->view->lpObjs = $listProdObj;
        
        // Categorie/SubCat array organizer
        $scoObj = new Model_Prodotti_SubCatOrganizer($listProd);
        $this->view->listProdotti = $scoObj->getListProductsCategorized();
        $this->view->listSubCat = $scoObj->getListCategories();
    }
    
    function qtaordineAction()
    {
        // GET PRODUCTS LIST with Qta Ordered
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($this->_ordine->idordine);
        $ordCalcObj = new Model_Ordini_Calcoli_Utenti();
        // SET ORDINE e PRODOTTI
        $ordCalcObj->setOrdObj($this->_ordine);
        $ordCalcObj->setProdotti($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
        $this->view->users = $ordCalcObj->getElencoUtenti();
    }
    
    function getformqtaAction() 
    {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        $this->view->iduser = $iduser = $this->getParam("iduser");
        $this->view->idprodotto = $idprodotto = $this->getParam("idprodotto");
        $this->view->idordine = $idordine = $this->getParam("idordine");
        // GET Prodotto ordinato
        $mObj = new Model_Db_Ordini();
        $prodotti = $mObj->getProdottiOrdinatiByIdordine($idordine, $iduser, $idprodotto);
        if(isset($prodotti[0])) {
            $pObj = new Model_Ordini_Prodotto($prodotti[0]);
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
                    $ordCalcObj->setOrdObj($this->_ordine);
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
    
    function newprodformAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        $this->view->iduser = $iduser = $this->getParam("iduser");
        $this->view->idordine = $idordine = $this->getParam("idordine");
        
        // GET All products available
        $ordObj = new Model_Db_Ordini();
        $listProd = $ordObj->getProdottiByIdOrdine($idordine);
        $arRes = array();
        if(is_array($listProd) && count($listProd) > 0) {
            foreach($listProd AS $prodotto) 
            {
                $arRes[] = array('id' => $prodotto->idprodotto, 'label' => $prodotto->descrizione, 'category' => $prodotto->categoria_sub);
            }
        }
        $this->view->arRes = json_encode($arRes);
        
        echo json_encode(array('res' => true, 'myTpl' => $this->view->fetch('gestioneordini/qtaordine-newprod.form.tpl.php')));

    }
    
    function newprodsaveAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
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
                    $ordCalcObj->setOrdObj($this->_ordine);
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
    
    function dettaglioAction() {
        
        // get View by Tipo
        $tipo = $this->getParam("tipo");
        if(is_null($tipo)) 
        {
            $tipo = "totali";
        }
        
        // GET PRODUCTS LIST with Qta Ordered
        $ordObj = new Model_Db_Ordini();
        $listProdOrdered = $ordObj->getProdottiOrdinatiByIdordine($this->_ordine->idordine);
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
        $ordCalcObj->setOrdObj($this->_ordine);
        $ordCalcObj->setProdotti($listProdOrdered);
        $this->view->ordCalcObj = $ordCalcObj;
    }
    
    function inviaAction() {
        // TODO: Invia email...
    }
    
    function movestatusAction() {
        
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        // MOVE order to the newStatus
        $newStatus = $this->getParam("newStatus");        
        $moverStatusObj = new Model_Ordini_Status_Mover($this->_ordine);
        $moved = $moverStatusObj->moveToStatus($newStatus);
        $result = array('res' => false);
        if($moved) {
            // GET new ORDER data
            $orderObj = new Model_Db_Ordini();
            $ordine = $orderObj->getByIdOrdine($this->_ordine->idordine);
            if($ordine) {
                $this->view->ordine = $ordine;
//                Zend_Debug::dump($this->view->ordine);
                $this->view->statusObj = new Model_Ordini_Status($ordine);
//                Zend_Debug::dump($this->view->statusObj);die;
                $myTpl = $this->view->fetch("gestioneordini/gestione-header.tpl.php");
                $result = array('res' => true, 'myTpl' => $myTpl);
            }
        }
        echo json_encode($result);
    }
    
    
}
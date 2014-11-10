<?php
/**
 * Description of Controller_Prodotti
 *
 * @author gullo
 */
class Controller_Prodotti extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    private $_produttore;
    private $_prodotto = null;
    
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->view->userSessionVal = $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
        
        // Try to GET Produttore
        $idproduttore = $this->getParam("idproduttore");
        if(is_null($idproduttore)) {
            // Try to GET Prodotto
            $idprodotto = $this->getParam("idprodotto");
            if(!is_null($idprodotto)) {
                $prodObj = new Model_Db_Prodotti();
                $prodotto = $prodObj->getProdottoById($idprodotto);
                if(!is_null($prodotto)) {
                    $this->_prodotto = $this->view->prodotto = $prodotto;
                    $idproduttore = $prodotto->idproduttore;
                }
            }
        }
        if(is_null($idproduttore)) {
            $this->redirect("index", "error", array('code' => 404));
        }
        $produttoreObj = new Model_Db_Produttori();
        $this->_produttore = $this->view->produttore = $produttoreObj->getProduttoreById($idproduttore);
        
        // Get updated if it is set
        $this->view->updated = $this->getParam("updated");        
        
    }

    function indexAction() {
        $this->forward("produttori");
    }

    
    function listAction() {
        
        // get All Prodotti by Produttore
        $objModel = new Model_Db_Prodotti();
        $listProd = $objModel->getProdottiByIdProduttore($this->_produttore->idproduttore);
        
        // BUILD Prodotti Anagrafica object
        $prodotti = new Model_AnagraficaProdotti();
        $prodotti->appendProdotti();
        $prodotti->appendCategorie();
        
        // init Prodotti by Object
        $prodotti->initProdotti_ByObject($listProd);
        
        // get Categories from $listProd (array Prodotti)
        $prodotti->initCategorie_ByObject($listProd);
        
        $this->view->prodotti = $prodotti;
//        Zend_Debug::dump($prodotti->getCategorie()->getChild(8)->getChild(47)->getProdotti());
    }

    function editAction() {
        
        // check REFERENTE, controllo per i furbi (non Referenti)
        if(!$this->_userSessionVal->refObject->canEditProdotti($this->_produttore->idproduttore)) {
            $this->redirect("index", "error", array('code' => 401));
        }
        
        if(is_null($this->_prodotto)) 
        {
            $this->redirect("prodotti", "list");
        }
        
        // Build Prodotto
        $prodotto = new Model_Prodotto_Mediator_Mediator();
        $prodotto->initByObject($this->_prodotto);
        
        // init a new form Prodotti
        $form = new Form_Prodotti();
        $form->setAction("/prodotti/edit/idprodotto/" . $prodotto->getIdProdotto());
        // remove useless fields
        $form->removeField("offerta");
        $form->removeField("sconto");
        
        // set Categories
        $objCat = new Model_Db_Categorie();
        $form->getField("idsubcat")
             ->setOptions($objCat->convertToSingleArray($objCat->getSubCategoriesByIdproduttore($this->_prodotto->idproduttore), "idsubcat", "descrizione"));
        
        // set array values Udm that need Multiplier
        $this->view->arValWithMultip = json_encode( Model_Prodotto_UdM::getArWithMultip() );
        
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // save Prodotto, after overwriting the fields values with form values
                $prodotto->initByObject($fv);
                $this->getDB()->makeUpdate("prodotti", "idprodotto", $prodotto->getAnagraficaValues());
                
                // REDIRECT
                $this->redirect("prodotti", "list", array("idproduttore" => $this->_prodotto->idproduttore, "updated" => $prodotto->getIdProdotto()));
            }
        } else {
            $form->setValues($prodotto->getValues());
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
    }

    
    function addAction() {
        
        // check REFERENTE, controllo per i furbi (non Referenti)
        if(!$this->_userSessionVal->refObject->canAddProdotti($this->_produttore->idproduttore)) {
            $this->redirect("index", "error", array('code' => 401));
        }
                
        $idproduttore = $this->_produttore->idproduttore;
        
        $form = new Form_Prodotti();
        $form->setAction("/prodotti/add/idproduttore/$idproduttore");
        $form->setValue("idproduttore", $idproduttore);
        // remove useless fields
        $form->removeField("attivo");
        $form->removeField("udm");
        $form->removeField("moltiplicatore");
        $form->removeField("costo");
        $form->removeField("aliquota_iva");
        $form->removeField("offerta");
        $form->removeField("sconto");
        $form->removeField("note");
        $form->removeField("idprodotto");
        
        // set Categories
        $objCat = new Model_Db_Categorie();
        $form->getField("idsubcat")
             ->setOptions($objCat->convertToSingleArray($objCat->getSubCategoriesByIdproduttore($idproduttore), "idsubcat", "descrizione"));
        
        if($this->getRequest()->isPost()) 
        {
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) 
            {
                $values = $form->getValues();
                $values["iduser_creator"] = $this->_iduser;
                // ADD NEW Prodotto
                $idprodotto = $this->getDB()->makeInsert("prodotti", $values);

                // REDIRECT to EDIT
                $this->redirect("prodotti", "edit", array("idprodotto" => $idprodotto));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }

}
<?php
/**
 * Description of Controller_Prodotti
 *
 * @author gullo
 */
class Controller_Prodotti extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() {
        $this->forward("produttori");
    }

    
    function listAction() {
        
        $idproduttore = $this->getParam("idproduttore");
        // Get updated if it is set
        $this->view->updated = $this->getParam("updated");
        
        $prodModel = new Model_Produttori();
        $produttore = $prodModel->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        if($produttore === false) {
            $this->forward("produttori");
        }
        // ADD Referente object to Produttore (so I can check the ref directly into the view)
        $produttore->refObj = new Model_Produttori_Referente($produttore->iduser_ref);
        $this->view->produttore = $produttore;
        
        // get All Prodotti by Produttore
        $objModel = new Model_Prodotti();
        $listProd = $objModel->getProdottiByIdProduttore($idproduttore);
        $listProdObj = array();
        if(count($listProd) > 0)
        {
            foreach ($listProd as $value)
            {
                $listProdObj[$value->idprodotto] = new Model_Prodotti_Prodotto($value);
            }
        }
        $this->view->lpObjs = $listProdObj;        
        
        // organize by category and subCat
        $scoObj = new Model_Prodotti_SubCatOrganizer($listProd);
        $this->view->listProdotti = $scoObj->getListProductsCategorized();
        $this->view->listSubCat = $scoObj->getListCategories();
//        Zend_Debug::dump($this->view->listProdotti);die;
    }

    function editAction() {

        $idprodotto = $this->getParam("idprodotto");        
        // check if CAN edit this Produttore
        $myObj = new Model_Prodotti();
        $prodotto = $myObj->getProdottoById($idprodotto);
        if($prodotto === false) {
            $this->redirect("prodotti", "list");
        }

        $form = new Form_Prodotti();
        $form->setAction("/prodotti/edit/idprodotto/$idprodotto");
        // remove useless fields
        $form->removeField("offerta");
        $form->removeField("sconto");
        
        // set Categories
        $objCat = new Model_Categorie();
        $form->setOptions("idsubcat", $objCat->convertToSingleArray($objCat->getSubCategories($this->_userSessionVal->idgroup, $prodotto->idproduttore), "idsubcat", "descrizione"));

        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {

                $this->getDB()->makeUpdate("prodotti", "idprodotto", $form->getValues());
                // REDIRECT
                $this->redirect("prodotti", "list", array("idproduttore" => $prodotto->idproduttore, "updated" => true));
            }
        } else {
            $form->setValues((array)$prodotto);
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
    }

    
    function addAction() {
        
        $idproduttore = $this->getParam("idproduttore");
        
        $form = new Form_Prodotti();
        $form->setAction("/prodotti/add/idproduttore/$idproduttore");
        $form->setValue("idproduttore", $idproduttore);
        // remove useless fields
        $form->removeField("offerta");
        $form->removeField("sconto");
        $form->removeField("idprodotto");

        // set Categories
        $objCat = new Model_Categorie();
        $form->setOptions("idsubcat", $objCat->convertToSingleArray($objCat->getSubCategories($this->_userSessionVal->idgroup, $idproduttore), "idsubcat", "descrizione"));
        
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Produttore
                $this->getDB()->makeInsert("prodotti", $form->getValues());

                $this->view->added = true;
                // REDIRECT
                $this->redirect("prodotti", "list", array("idproduttore" => $fv["idproduttore"]));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }




}
?>

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

    }

    
    function listAction() {
        
        $idproduttore = $this->getParam("idproduttore");
        $prodModel = new Model_Produttori();
        $produttore = $prodModel->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        $produttore->refObj = new Model_Produttori_Referente($produttore->iduser_ref);
        $this->view->produttore = $produttore;
        
        // get All Prodotti by Produttore
        $objModel = new Model_Prodotti();
        $list = $objModel->getProdottiByIdProduttore($idproduttore);
//        Zend_Debug::dump($sth->rowCount()); die;
        $this->view->list = $list;
    }

    function editAction() {

        $idprodotto = $this->getParam("idprodotto");
        $this->view->updated = false;
        
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

        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {

                $this->getDB()->makeUpdate("prodotti", "idprodotto", $form->getValues());

                $this->view->updated = true;
                
                $this->redirect("prodotti", "list", array("idproduttore" => $prodotto["idproduttore"]));
            }
            //Zend_Debug::dump($sth); die;
            
        } else {
            $form->setValues($prodotto);
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
    }

    
    function addAction() {
        
        $form = new Form_Prodotti();
        $form->setAction("/prodotti/add");
        $form->setValue("idproduttore", $this->getParam("idproduttore"));
        // remove useless fields
        $form->removeField("offerta");
        $form->removeField("sconto");
        $form->removeField("idprodotto");

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Produttore
                $idprodotto = $this->getDB()->makeInsert("prodotti", $form->getValues());

                $this->view->added = true;
                
                $this->redirect("prodotti", "list", array("idproduttore" => $fv["idproduttore"]));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }




}
?>

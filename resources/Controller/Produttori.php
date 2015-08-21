<?php
/**
 * Description of Index
 *
 * @author gullo
 */
class Controller_Produttori extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->view->userSessionVal = $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() 
    {    
        $pObj = new Model_Db_Produttori();
        $listProduttori = $pObj->getProduttori();
        $referenti = $pObj->getReferentiByIdgroup_withKeyIdProduttore($this->_userSessionVal->idgroup);
        // create array PRODUTTORI with objects Model_Produttori_Produttore
        $listProduttoriOrdered = array();
        if(count($listProduttori) > 0) {
            foreach($listProduttori AS $prod) 
            {
                // create Produttore
                $produttore = new Model_Produttori_Produttore();
                $produttore->initByArrayValues($prod);
                $refs = isset($referenti[$prod->idproduttore]) ? $referenti[$prod->idproduttore] : array();
                $produttore->setReferenti($refs);
                // IF IS Referente put the value on the TOP of array
                if( $this->_userSessionVal->permsProduttori->is_Referente($produttore->idproduttore) ) {
                    array_unshift($listProduttoriOrdered, $produttore);
                } else {
                    array_push($listProduttoriOrdered, $produttore);
                }
            }
        }
        $this->view->list = $listProduttoriOrdered;
        
        // Create array Categorie prodotti for Produttori
        $catObj = new Model_Db_Categorie();
        $arCat = $catObj->getCategories_withKeyIdProduttore();
        $this->view->arCat = $arCat;
        
    }

/*    
    function addAction() {
        
        $form = new Form_Produttori();
        $form->setAction("/produttori/add");
        $form->removeField("idproduttore");
        
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Produttore
                $idproduttore = $this->getDB()->makeInsert("produttori", $fv);

                // Add Relationship to referenti (REFERENTE PRODUTTORE)
                $this->getDB()->makeInsert("referenti", array(
                    'idproduttore'  => $idproduttore,
                    'idgroup'       => $this->_userSessionVal->idgroup,
                    'iduser_ref'    => $this->_iduser
                ));
     
                // Add Relationship to users_produttori (GESTORE PRODUTTORE)
                $this->getDB()->makeInsert("users_produttori", array(
                    'idproduttore'  => $idproduttore,
                    'iduser'    => $this->_iduser
                ));
                
                // REDIRECT TO EDIT
                $this->redirect("produttori", "edit", array('idproduttore' => $idproduttore));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
*/    
    function viewAction() {
        $idproduttore = $this->getParam("idproduttore");
        $myObj = new Model_Db_Produttori();
        $this->view->produttore = $myObj->getProduttoreById($idproduttore);
    }
    
    function editAction() {

        $idproduttore = $this->getParam("idproduttore");
        
        // check if CAN edit this Produttore
        $myObj = new Model_Db_Produttori();
        $produttore = $myObj->getProduttoreById($idproduttore);
        // Un po' di controlli per i furbi...
        if($produttore === false) {
            $this->redirect("produttori");
        }
        if(!$this->_userSessionVal->permsProduttori->canManageProduttore($idproduttore)) {
            $this->forward("produttori", "view", array('idproduttore' => $idproduttore));
        }
        $this->view->produttore = $produttore;
        
        // Get Form Produttori
        $form = new Form_Produttori();
        $form->setAction("/produttori/edit/idproduttore/$idproduttore");
        
        // Get elenco Categorie
        $catObj = new Model_Db_Categorie();
        $this->view->categorie = $catObj->convertToSingleArray($catObj->getCategorie(), "idcat", "descrizione");
        
        // Get POST and Validate data
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            // set arSubCat
            $arSubCat = array();
            if(isset($fv["arSubCat"])) {
                $arSubCat = $fv["arSubCat"];
                unset($fv["arSubCat"]);
            }
            unset($fv["idcat"]);
            
            if( $form->isValid($fv) ) {
                
                $this->getDB()->makeUpdate("produttori", "idproduttore", $fv);
                
                /* ADD CATEGORIES */
                if(count($arSubCat) > 0) {
                    $arVal = array();
                    // prepare array to UPDATE!
                    foreach ($arSubCat as $idsubcat => $subCat) {
                        $arVal[] = array(
                            'idsubcat'      => $idsubcat,
                            'descrizione'   => $subCat["descrizione"],
                            'idcat'         => $subCat["idcat"]
                        );
                    }
                    $catObj->editSubCategorie($arVal);
                }

                $this->redirect("produttori", "edit", array('idproduttore' => $idproduttore, 'updated' => true));
            }
        } else {
            $form->setValues($produttore);
        }
        
        // get Elenco subCat
        $this->view->arSubCat =  $catObj->getSubCategoriesByIdproduttore($idproduttore);
        // set Form in the View
        $this->view->form = $form;
        $this->view->updated = $this->getParam("updated");        
    }


    

/******************
 * JX Functions
 *****************/
    function addcatAction() {
        
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        $idproduttore = $this->getParam("idproduttore");
        $idcat = $this->getParam("idcat");
        $catName = $this->getParam("catName");
        
        // prepare array
        $arVal = array(
                'idproduttore'  => $idproduttore,
                'idcat'         => $idcat,
                'descrizione'   => $catName
            );
        $catObj = new Model_Db_Categorie();
        $idsubcat = $catObj->addSubCategoria($arVal);
        
        if(!is_null($idsubcat)) {
            $arVal["idsubcat"] = $idsubcat;
            // set data in view of the new Subcat created
            $this->view->subCat = $arVal;
            // Get elenco Categorie
            $catObj = new Model_Db_Categorie();
            $this->view->categorie = $catObj->convertToSingleArray($catObj->getCategorie(), "idcat", "descrizione");
            // fetch View
            $myTpl = $this->view->fetch("produttori/form.cat-single.tpl.php");
            $result = array('res' => true, 'myTpl' => $myTpl);
        } else {
            $result = array('res' => false);
        }
        //Zend_Debug::dump($result);die;
        echo json_encode($result);
    }
    
    function delcatAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        $idsubcat = $this->getParam("idsubcat");
        // get Prodotti by idsubcat
        $prodObj = new Model_Db_Prodotti();
        $prodotti = $prodObj->getProdottiByIdSubCat($idsubcat);
        if(count($prodotti) > 0) {
            
            $result = array('res' => false, 'prodotti' => $prodotti);
        } else {
            // Nessun prodotto per questa SubCat -> can DELETE!
            $sth = $this->getDB()->prepare("DELETE FROM categorie_sub WHERE idsubcat= :idsubcat");
            $res = $sth->execute(array('idsubcat' => $idsubcat));
            $result = array('res' => $res);
        }
        
        echo json_encode($result);
    }
    
}
?>

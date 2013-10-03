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
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() {
        
        // get All Produttori by Group
        $idgroup = $this->_userSessionVal->idgroup;
        $sql = "SELECT p.*, gp.stato, gp.iduser_ref, u.nome, u.cognome "
              ." FROM produttori AS p"
              ." LEFT JOIN groups_produttori AS gp ON p.idproduttore=gp.idproduttore"
              ." LEFT JOIN users AS u ON gp.iduser_ref=u.iduser"
              ." WHERE gp.idgroup= :idgroup"
              ." ORDER BY p.ragsoc";
        //echo $sql; die;
        $sth = $this->getDB()->prepare($sql);
        $sth->execute(array('idgroup' => $this->_userSessionVal->idgroup));
        $listProduttori = $sth->fetchAll(PDO::FETCH_CLASS);
        
        // add Status model to Ordini
        if(count($listProduttori) > 0) {
            foreach($listProduttori AS &$produttore) {
                $produttore->refObj = new Model_Produttori_Referente($produttore->iduser_ref);
            }
        }

        $this->view->list = $listProduttori;
    }

    
    function addAction() {
        
        $form = new Form_Produttori();
        $form->setAction("/produttori/add");
        $form->removeField("idproduttore");
        
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                
                // ADD Produttore
                $idproduttore = $this->getDB()->makeInsert("produttori", $fv);

                // Add Relationship with Group
                $this->getDB()->makeInsert("groups_produttori", array(
                    'idproduttore'  => $idproduttore,
                    'idgroup'       => $this->_userSessionVal->idgroup,
                    'stato'         => 'A',
                    'iduser_ref'    => $this->_iduser
                ));
     
                // REDIRECT TO EDIT
                $this->redirect("produttori", "edit", array('idproduttore' => $idproduttore));
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    
    function viewAction() {
        $idproduttore = $this->getParam("idproduttore");
        $myObj = new Model_Produttori();
        $this->view->produttore = $myObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
    }
    
    function editAction() {

        $idproduttore = $this->getParam("idproduttore");
        
        $this->view->updated = false;
        
        // check if CAN edit this Produttore
        $myObj = new Model_Produttori();
        $produttore = $myObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        // Un po' di controlli per i furbi...
        if($produttore === false) {
            $this->redirect("produttori");
        }
        if($produttore->iduser_ref != $this->_iduser) {
            $this->forward("produttori", "view", array('idproduttore' => $idproduttore));
        }
        $this->view->produttore = $produttore;
        
        // Get Form Produttori
        $form = new Form_Produttori();
        $form->setAction("/produttori/edit/idproduttore/$idproduttore");
        
        // Get elenco Categorie
        $catObj = new Model_Categorie();
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
                            'descrizione'   => $subCat
                        );
                    }
                    $catObj->editSubCategorie($arVal);
                }

                $this->view->updated = true;
            }
            //Zend_Debug::dump($sth); die;
            
        } else {
            $form->setValues($produttore);
        }
        
        // get Elenco subCat
        $this->view->arSubCat =  $catObj->getSubCategories($this->_userSessionVal->idgroup, $idproduttore);
        // set Form in the View
        $this->view->form = $form;
    }


    

/******************
 * JX Functions
 *****************/
    function addcatAction() {
        
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        $idproduttore = $this->getParam("idproduttore");
        $idcat = $this->getParam("idcat");
        $catName = $this->getParam("catName");;
        
        // prepare array
        $arVal = array(
                'idgroup'       => $this->_userSessionVal->idgroup,
                'idproduttore'  => $idproduttore,
                'idcat'         => $idcat,
                'descrizione'   => $catName
            );
        
        $catObj = new Model_Categorie();
        $idsubcat = $catObj->addSubCategoria($arVal);
        
        if(!is_null($idsubcat)) {
            $result = array('res' => true, 'idsubcat' => $idsubcat);
        } else {
            $result = array('res' => false);
        }
        
        echo json_encode($result);
    }
    
    function delcatAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        
        $idsubcat = $this->getParam("idsubcat");
        $sth = $this->getDB()->prepare("DELETE FROM categorie_sub WHERE idsubcat= :idsubcat");
        $result = $sth->execute(array('idsubcat' => $idsubcat));
        echo json_encode($result);
    }
    
}
?>

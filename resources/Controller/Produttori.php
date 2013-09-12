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

    
    function editAction() {

        $idproduttore = $this->getParam("idproduttore");
        
        // check if CAN edit this Produttore
        $myObj = new Model_Produttori();
        $produttore = $myObj->getProduttoreById($idproduttore, $this->_userSessionVal->idgroup);
        if($produttore === false) {
            $this->redirect("produttori");
        }        
        
        $form = new Form_Produttori();
        $form->setAction("/produttori/edit/idproduttore/$idproduttore");
        
        // Get elenco Categorie
        $catObj = new Model_Categorie();
        $this->view->categorie = $catObj->convertToSingleArray($catObj->getCategorie(), "idcat", "descrizione");
        $this->view->arSubCat = array();
        $arSubCat = $catObj->getSubCategories($this->_userSessionVal->idgroup, $idproduttore);
        if(count($arSubCat) > 0) {
            foreach ($arSubCat as $key => $value) {
                $this->view->arSubCat[$value["idcat"]][$value["idsubcat"]] = $value["descrizione"];
            }
        }
        
        // Get POST and Validate data
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            // set arSubCat
            //Zend_Debug::dump($fv["arSubCat"]); die;
            if(isset($fv["arSubCat"])) {
                $this->view->arSubCat = $fv["arSubCat"];
                unset($fv["arSubCat"]);
            }
            unset($fv["idcat"]);
            
            if( $form->isValid($fv) ) {
                
                $this->getDB()->makeUpdate("produttori", "idproduttore", $fv);
                
                // ADD CATEGORIES
                $sth = $this->getDB()->prepare("DELETE FROM categorie_sub WHERE idgroup= :idgroup AND idproduttore= :idproduttore");
                $sth->execute(array('idgroup' => $this->_userSessionVal->idgroup, 'idproduttore' => $idproduttore));
                die;
                if(count($this->view->arSubCat)) {
                    foreach ($this->view->arSubCat as $idcat => $arCat) {
                        foreach ($arCat as $idsubcat => $subCatDesc) {
                            $this->getDB()->makeInsert("categorie_sub", array(
                                'idsubcat'      => $idsubcat,
                                'idproduttore'  => $idproduttore,
                                'idgroup'       => $this->_userSessionVal->idgroup,
                                'idcat'         => $idcat,
                                'descrizione'   => $subCatDesc
                            ));
                        }
                    }
                }
                

                $this->view->updated = true;
            }
            //Zend_Debug::dump($sth); die;
            
        } else {
            $form->setValues($produttore);
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
    }

    
    function addAction() {
        
        $form = new Form_Produttori();
        $form->setAction("/produttori/add");
        $form->removeField("idproduttore");
        
        // Get elenco Categorie
        $catObj = new Model_Categorie();
        $this->view->categorie = $catObj->convertToSingleArray($catObj->getCategorie(), "idcat", "descrizione");
        $this->view->arSubCat = array();

        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            // set arSubCat
            if(isset($fv["arSubCat"])) {
                $this->view->arSubCat = $fv["arSubCat"];
                unset($fv["arSubCat"]);
            }
            unset($fv["idcat"]);
            
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
                
                // ADD CATEGORIES
                if(count($this->view->arSubCat)) {
                    foreach ($this->view->arSubCat as $idcat => $arCat) {
                        foreach ($arCat as $k => $subCatDesc) {
                            $this->getDB()->makeInsert("categorie_sub", array(
                                'idproduttore'  => $idproduttore,
                                'idgroup'       => $this->_userSessionVal->idgroup,
                                'idcat'         => $idcat,
                                'descrizione'   => $subCatDesc
                            ));
                        }
                    }
                }
                
                $this->view->added = true;
            }
        }
        
        // set Form in the View
        $this->view->form = $form;
    }
    



}
?>

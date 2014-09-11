<?php
/**
 * Description of Listini
 *
 * @author gullo
 */
class Controller_Listini extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() 
    {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->view->userSessionVal = $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() 
    {
        // get Elenco Listini per Gruppo
        $lObj = new Model_Db_Listini();
        $cObj = new Model_Db_Categorie();
        $listiniArray = $lObj->getListiniByIdgroup($this->_userSessionVal->idgroup);
        $listini = array();
        if(!is_null($listiniArray)) {
            foreach ($listiniArray as $stdListino) {
                // creates Listino by Abstract Factory Model_AF_ListinoFactory
                $mllObj = new Model_Listini_Listino();
                // init Dati by stdClass
                $mllObj->setDati($stdListino);
                
                // set Categories in Listini object
                $categorie = $cObj->getCategoriesByIdListino( $mllObj->getDati()->getIdListino() );
                // get CATEGORIE by array 
                $catObj = new Model_Builder_Categorie();
                $catObj->initDatiByObject($categorie);
                $mllObj->setCategorie($catObj);
                
                // check for Referente Listino
                if( $mllObj->canManageListino() ) {
                    array_unshift($listini, $mllObj);
                } else {
                    array_push($listini, $mllObj);
                }
            }
        }
        
        $this->view->listini = $listini;
        //Zend_Debug::dump($listini);
    }
    
    function addAction()
    {
        // init Listino form
        $form = new Form_Listino();
        $form->setAction("/listini/add");
        $form->removeField("idlistino");
        $form->removeField("valido_dal");
        $form->removeField("valido_al");
        $form->removeField("condivisione");
        $form->removeField("visibile");
        $form->removeField("idproduttore");
        
        // get Produttori
        $pObj = new Model_Db_Produttori();
        // modify inline the idproduttore field
        $form->addField('idproduttore', array(
                        'label'     => 'Produttore',
                        'type'      => 'select',
                        'required'  => true,
                        'options'   => $pObj->convertToSingleArray($pObj->getProduttoriByIdRef($this->_iduser), "idproduttore", "ragsoc")
            ));

        if($this->getRequest()->isPost()) {
            // get Post values
            $fv = $this->getRequest()->getPost();
            // check if values are valid
            if( $form->isValid($fv) ) 
            {   
                // create NEW Listino
                $mllObj = new Model_Listini_Listino();
                $mllObj->getDati()->setDescrizione($form->getValue("descrizione"));
                $mllObj->getDati()->setIdProduttore($form->getValue("idproduttore"));
                $mllObj->getDati()->setCondivisione("PRI"); // Default is Private
                if( $mllObj->getDati()->saveToDB() ) {
                    $idlistino = $mllObj->getDati()->getIdListino();
                    // create a NEW group
                    $group = new stdClass();
                    $group->id = $idlistino;
                    $group->idgroup_master = $this->_userSessionVal->idgroup;
                    $group->idgroup_slave = $this->_userSessionVal->idgroup;
                    // add my group
                    $mllObj->getGroups()->addGroup($group);
                    $resSave = $mllObj->getGroups()->saveToDB();

                    // REDIRECT to EDIT
                    if($resSave) {
                        $this->redirect("listini", "edit", array('idlistino' => $idlistino, 'updated' => true));
                    }
                }
            }            
        }
        // set Form in the View
        $this->view->form = $form;        
    }
    
    function editAction()
    {
        $idlistino = $this->getParam("idlistino");
        if(is_null($idlistino)) 
        {
            $this->redirect("listini", "index");
        }
        
        // init Listino DB Model to get data
        $lObj = new Model_Db_Listini();
        $listino = $lObj->getListinoById($idlistino);

        // check REFERENTE, controllo per i furbi (non Referenti)
        if(!$this->_userSessionVal->refObject->canEditProdotti($listino->idproduttore)) {
            $this->redirect("index", "error", array('code' => 401));
        }
        
        // Create Listino Object
        $mllObj = new Model_Listini_Listino();
        // set DATI in Listino
        $mllObj->setDati($listino);
        // set GROUPS in Listino
        $mllObj->setGroups( $lObj->getGroupsByIdlistino($idlistino) )
               ->setMyIdGroup($this->_userSessionVal->idgroup);
        //Zend_Debug::dump($mllObj->getGroups());die;
        // add All PRODOTTI by Listino
        $objModel = new Model_Db_Prodotti();
        $prodotti = $objModel->getProdottiByIdListino($idlistino);
        $mllObj->setProdotti( $prodotti );
        
        // get CATEGORIE by array $prodotti
        $catObj = new Model_Builder_Categorie();
        $catObj->initDatiByObject($prodotti);
        $mllObj->setCategorie($catObj);
            
        // get elenco All Groups
        $grObj = new Model_Db_Groups();
        $this->view->groups = $groups = $grObj->getAll();
        
        // init Listino form
        $form = new Form_Listino();
        $form->setAction("/listini/edit/idlistino/$idlistino");

        if($this->getRequest()->isPost()) {
            // get Post values
            $fv = $this->getRequest()->getPost();

            // set values null for validita if it was not set
            if( $fv["validita"] != "S" ) {
                $fv["valido_dal"] = $fv["valido_al"] = null;
            }
            // check if values are valid
            if( $form->isValid($fv) ) 
            {   
                // Save DATI
                $mllObj->getDati()->setDescrizione($form->getValue("descrizione"));
                $mllObj->getDati()->setCondivisione($form->getValue("condivisione"));
                $mllObj->getGroups()->getMyGroup()->setValidita($form->getValue("valido_dal"), $form->getValue("valido_al"));
                $mllObj->getGroups()->getMyGroup()->setVisibile( $form->getValue("visibile") );
                
                // Save GROUPS
                $groupsToShare = isset($fv["groups"]) ? $fv["groups"] : array();
                $mllObj->getGroups()->resetGroups($form->getValue("condivisione"), $groupsToShare);
                
                // Save PRODUCTS
                /**
                 * @todo 
                 */
                
//                Zend_Debug::dump($mllObj);die;
                // SAVE ALL DATA CHANGED TO DB
                $resSave = $mllObj->save();
                
                // REDIRECT
                if($resSave) {
                    $this->redirect("listini", "edit", array('idlistino' => $idlistino, 'updated' => true));
                }
            }
        } else {
            // build array values for form
            $form->setValues($mllObj->getDati()->getValues());
            // set some values in the right format
            $form->setValue("valido_dal", $mllObj->getGroups()->getMyGroup()->getValidita()->getDal("dd/MM/YYYY"));
            $form->setValue("valido_al", $mllObj->getGroups()->getMyGroup()->getValidita()->getAl("dd/MM/YYYY"));
            $form->setValue("visibile", $mllObj->getGroups()->getMyGroup()->getVisibile()->getString());
        }
        
        $this->view->listino = $mllObj;
        // set Form in the View
        $this->view->form = $form;
        $this->view->updated = $this->getParam("updated");   
        // Zend_Debug::dump($master); die;
    }
    
    function viewAction()
    {
        
    }
    
}
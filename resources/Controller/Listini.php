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
        $lObj = new Model_Listini();
        $listiniArray = $lObj->getListiniByIdgroup($this->_userSessionVal->idgroup);
        $listini = array();
        if(!is_null($listiniArray)) {
            foreach ($listiniArray as $stdListino) {
                $mllObj = new Model_Listini_Listino($stdListino);
                // set Categories in Listini object
                $categories = $lObj->getCategoriesByIdlistino($mllObj->getDati()->idlistino);
                $mllObj->setCategories($categories);
                
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
        
    }
    
    function editAction()
    {
        $idlistino = $this->getParam("idlistino");

        // init Listino object
        $lObj = new Model_Listini();
        $listino = $lObj->getListinoById($idlistino);
        $mllObj = new Model_Listini_Listino($listino);
        $mllObj->setMyIdGroup($this->_userSessionVal->idgroup);
        // set Groups in Listini object
        $mllObj->initGroupsFromDb( $lObj->getGroupsByIdlistino($idlistino) );
        
        // get elenco Groups
        $grObj = new Model_Groups();
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
                $mllObj->getDati()->descrizione = $form->getValue("descrizione");
                $mllObj->getDati()->condivisione = $form->getValue("condivisione");
                $mllObj->getMyGroup()->setValidita($form->getValue("valido_dal"), $form->getValue("valido_al"));
                $mllObj->getMyGroup()->setVisibile( $form->getValue("visibile") );
                
                // Save GROUPS
                $mllObj->resetGroups($fv["groups"]);
                
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
            $form->setValue("valido_dal", $mllObj->getMyGroup()->getValidita()->getDal("dd/MM/YYYY"));
            $form->setValue("valido_al", $mllObj->getMyGroup()->getValidita()->getAl("dd/MM/YYYY"));
            $form->setValue("visibile", $mllObj->getMyGroup()->getVisibile()->getString());
        }
        
        $this->view->listino = $mllObj;
        // set Form in the View
        $this->view->form = $form;
        $this->view->updated = $this->getParam("updated");   
        // Zend_Debug::dump($master); die;
    }
    
    function prodottiAction()
    {
        
    }
    
    function viewAction()
    {
        
    }
    
}
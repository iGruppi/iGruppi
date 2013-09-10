<?php
/**
 * Description of Users
 * 
 * @author gullo
 */
class Controller_Users extends MyFw_Controller {
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    
    function indexAction() {
        
    }
    
    function editAction() {
        $iduser = $this->getParam("iduser");
        
        // check if CAN edit this Produttore
        $uObj = new Model_Users();
        $user = $uObj->getUserByIdInGroup($iduser, $this->_userSessionVal->idgroup);
        if($user === false) {
            $this->redirect("gruppo", "iscritti");
        }
        
        $form = new Form_User();
        $form->setAction("/users/edit/iduser/$iduser");
        // remove useless fields
        $form->removeField("password");
        $form->removeField("password2");
        $form->removeField("idgroup");

        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {

                $values = $form->getValues();
                // remove users_group fields
                $attivo = $values["attivo"];
                unset($values["attivo"]);
                $fondatore = $values["fondatore"];
                unset($values["fondatore"]);
                $contabile = $values["contabile"];
                unset($values["contabile"]);
                
                // SAVE user data
                $this->getDB()->makeUpdate("users", "iduser", $values);
                
                // SET users_group flags
                $sth = $this->getDB()->prepare("UPDATE users_group SET attivo= :attivo, fondatore= :fondatore, contabile= :contabile WHERE iduser= :iduser AND idgroup= :idgroup");
                $fields = array('attivo' => $attivo, 'fondatore' => $fondatore, 'contabile' => $contabile, 'iduser' => $iduser, 'idgroup' => $this->_userSessionVal->idgroup);
                $sth->execute($fields);

                $this->view->updated = true;
                
                $this->redirect("gruppo", "iscritti");
            }
            
            
        } else {
            $form->setValues($user);
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
        
    }
/*    
    function addAction() {
        
        $name = $this->getParam("name");
        $surname = $this->getParam("surname");
        $data = date("Y-m-d H:i:s");
        $db = Zend_Registry::get("db");
        $db->query("INSERT INTO users SET name='$name', surname='$surname', dt='$data'");
        
        $this->forward('users', 'manage');
    }
    */
    
}
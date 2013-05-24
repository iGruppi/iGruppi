<?php
/**
 * Description of Auth
 * 
 * @author gullo
 */
class Controller_Auth extends MyFw_Controller {
    
    
    function indexAction() {
        $this->forward("auth", "register");
    }
    
    
    function loginAction() {
        
        $form = new Form_Login();
        $form->setAction("/auth/login");
        
        // reset errorLogin
        $this->view->errorLogin = false;
        
        if($this->getRequest()->isPost()) {
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {
                // check Auth
                $checkSth = $this->getDB()->prepare("SELECT * FROM users WHERE email= :email AND password= :password");
                $checkSth->execute(array('email' => $form->getValue("email"), 'password' => $form->getValue('password')));
                if( $checkSth->rowCount() > 0 ) {
                    // store user values
                    $auth = Zend_Auth::getInstance();
                    $auth->clearIdentity();
                    $storage = $auth->getStorage();
                    // remove password
                    $row = $checkSth->fetch(PDO::FETCH_OBJ);
                    $storage->write($row);
                    
                    // redirect to Dashboard
                    $this->redirect('dashboard');
                    
                } else {
                    // Set ERROR: ACCOUNT NOT VALID!!
                    $this->view->errorLogin = "Email and/or Password are wrong!";
                }
            }
            //Zend_Debug::dump($sth); die;
            
        }
        // Zend_Debug::dump($form); die;
        // set Form in the View
        $this->view->form = $form;
        
    }

    function logoutAction() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();

        Zend_Session::destroy();
        $this->redirect('index');
    }

    function registerAction() {

        $form = new Form_User();
        $form->setAction("/auth/register");

        // reset errorLogin
        $this->view->errorRegister = false;

        if($this->getRequest()->isPost()) {

            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {

                // ADD User
                if( $form->getValue("password") != $form->getValue("password2") ) {
                    $form->setError("password2", "Riscrivi correttamente la password");
                } else {
                    // remove password2 field
                    $form->removeField("password2");

                    // get idgroup
                    $idgroup = $form->getValue("idgroup");
                    echo "idgroup: $idgroup";
                    $form->removeField("idgroup");

                    $iduser = $this->getDB()->makeInsert("users", $fValues);
                    $this->view->added = true;
                }
            }
        }

        // set Form in the View
        $this->view->form = $form;

    }
    
}
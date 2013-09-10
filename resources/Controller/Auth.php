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
        // remove useless fields
        $form->removeField("iduser");
        $form->removeField("attivo");
        $form->removeField("fondatore");
        $form->removeField("contabile");

        // reset errorLogin
        $this->view->added = false;

        if($this->getRequest()->isPost()) {

            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            if( $form->isValid($fv) ) {

                // ADD User
                $fValues = $form->getValues();
                if( $fValues["password"] != $fValues["password2"] ) {
                    $form->setError("password2", "Riscrivi correttamente la password");
                } else {
                    try {
                        // remove password2 field
                        unset($fValues["password2"]);
                        // get idgroup
                        $idgroup = $fValues["idgroup"];
                        unset($fValues["idgroup"]);

                        $gObj = new Model_Groups();
                        $group = $gObj->getGroupById($idgroup);
                        if($group !== false) {

                            // ADD USER
                            $iduser = $this->getDB()->makeInsert("users", $fValues);
                            
                            // ADD USER TO GROUP
                            $ugFields = array(
                                'iduser' => $iduser,
                                'idgroup'=> $idgroup
                            );
                            $this->getDB()->makeInsert("users_group", $ugFields);
                            
                            // Get Founder of the Group
                            $ugObj = $gObj->getGroupFoundersById($idgroup);
                            if(count($ugObj) > 0) {
                                // INVIO EMAIL al FONDATORE per NUOVO UTENTE
                                $mail = new MyFw_Mail();
                                $mail->setSubject("Nuovo iscritto al Gruppo ".$group->nome);
                                foreach($ugObj AS $ugVal) {
                                    $mail->addTo($ugVal["email"]);
                                }
                                $mail->setViewParam("new_user", $fValues["nome"] . " " . $fValues["cognome"] );
                                $mail->setViewParam("gruppo", $group->nome);
                                $mail->setViewParam("email_user", $fValues["email"]);
                                $this->view->sended = $mail->sendHtmlTemplate("notify.new_user_subscribe.tpl.php");
                            }

                            // OK!
                            $this->view->added = true;
                        } else {
                            $form->setError("idgroup", "Devi selezionare un gruppo esistente!");
                        }
                    } catch (Exception $exc) {
                        echo $exc->getTraceAsString();
                    }
                }
            }
        }

        // set Form in the View
        $this->view->form = $form;

    }
    
}
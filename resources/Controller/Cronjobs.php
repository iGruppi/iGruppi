<?php
/**
 * Description of Cronjobs
 * 
 * @author gullo
 */
class Controller_Cronjobs extends MyFw_Controller {
    
    private $_dateTomorrow;
    
    function _init() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
        // some usefull date
        $today = new DateTime();
        $tomorrow = $today->add( new DateInterval("P1D")); // add 1 day
        $this->_dateTomorrow = $tomorrow->format(MyFw_Form_Filters_Date::_MYFORMAT_DATE_DB);
    }
    
    function everyhourAction() {
        $this->startorderAction();
        $this->closeorderAction();
    }

    function startorderAction() 
    {
        // GET any ORDER that will be Aperto TOMORROW
        $ordObj = new Model_Db_Ordini();
        $listOrd = $ordObj->getAllByDate($this->_dateTomorrow, "data_inizio");
        $cObj = new Model_Db_Categorie();
        // create array of Ordini objects
        $ordini = array();
        if(count($listOrd) > 0) {
            foreach($listOrd AS $ordine) 
            {
                // BUILD Ordine object with a Chain of objects
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                //$mooObj->enableDebug();
                $mooObj->appendDati();
                $mooObj->appendCategorie();
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );
                
                // init Dati by stdClass
                $mooObj->initDati_ByObject($ordine);
                
                // build & init Gruppi
                $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
                
                // init Categorie by IdOrdine
                $categorie = $cObj->getCategoriesByIdOrdine($mooObj->getIdOrdine());
                $mooObj->initCategorie_ByObject($categorie);
                //Zend_Debug::dump($catObj);
                
                
                // add Ordine object to the array
                $ordini[] = $mooObj;
            }
        }
        
        // send emails for every ordine to every user of the groups
        if(count($ordini) > 0) {
            // init Model_Db_Users
            $usersObj = new Model_Db_Users();
            
            foreach ($ordini as $key => $ordineObj) 
            {
                // get the list of Groups
                // $ordineObj->enableDebug();
                $groups = $ordineObj->getAllIdgroups();
                foreach($groups AS $idgroup) 
                {
                    // PREPARE EMAIL TO GROUP LIST
                    $mail = new MyFw_Mail();
                    $mail->setSubject("Apertura Nuovo ordine: ".$ordineObj->getDescrizione()." (#".$ordineObj->getIdOrdine().")");
                    $mail->setViewParam("ordine", $ordineObj);
                    $mail->setDefaultTo();
                    
                    // GET USERS LIST
                    $users = $usersObj->getUsersByIdGroup($idgroup, true);
                    if(count($users) > 0)
                    {
                        foreach($users AS $user)
                        {
                            $mail->addBcc($user->email);
                        }
                    }
                    
                    // SEND IT...
                    $mail->sendHtmlTemplate("order.start_open.tpl.php");
                }
            }
        }
    }
    
    function closeorderAction() 
    {
        // GET any ORDER that will be Aperto TOMORROW
        $ordObj = new Model_Db_Ordini();
        $listOrd = $ordObj->getAllByDate($this->_dateTomorrow, "data_fine");
        $cObj = new Model_Db_Categorie();
        // create array of Ordini objects
        $ordini = array();
        if(count($listOrd) > 0) {
            foreach($listOrd AS $ordine) 
            {
                // BUILD Ordine object with a Chain of objects
                $mooObj = new Model_Ordini_Ordine( new Model_AF_OrdineFactory() );
                //$mooObj->enableDebug();
                $mooObj->appendDati();
                $mooObj->appendCategorie();
                $mooObj->appendStates( Model_Ordini_State_OrderFactory::getOrder($ordine) );
                
                // init Dati by stdClass
                $mooObj->initDati_ByObject($ordine);
                
                // build & init Gruppi
                $mooObj->appendGruppi()->initGruppi_ByObject( $ordObj->getGroupsByIdOrdine( $mooObj->getIdOrdine()) );
                
                // init Categorie by IdOrdine
                $categorie = $cObj->getCategoriesByIdOrdine($mooObj->getIdOrdine());
                $mooObj->initCategorie_ByObject($categorie);
                //Zend_Debug::dump($catObj);
                
                
                // add Ordine object to the array
                $ordini[] = $mooObj;
            }
        }
        
        // send emails for every ordine to every user of the groups
        if(count($ordini) > 0) {
            // init Model_Db_Users
            $usersObj = new Model_Db_Users();
            
            foreach ($ordini as $key => $ordineObj) 
            {
                // get the list of Groups
                // $ordineObj->enableDebug();
                $groups = $ordineObj->getAllIdgroups();
                foreach($groups AS $idgroup) 
                {
                    // PREPARE EMAIL TO GROUP LIST
                    $mail = new MyFw_Mail();
                    $mail->setSubject("Chiusura ordine: ".$ordineObj->getDescrizione()." (#".$ordineObj->getIdOrdine().")");
                    $mail->setViewParam("ordine", $ordineObj);
                    $mail->setDefaultTo();
                    
                    // Get dati Gruppo
                    $groupObj = $ordineObj->getGroupByIdGroup($idgroup);
                    $mail->setViewParam("note_consegna", $groupObj->getNoteConsegna());
                    
                    // GET USERS LIST
                    $users = $usersObj->getUsersByIdGroup($idgroup, true);
                    if(count($users) > 0)
                    {
                        foreach($users AS $user)
                        {
                            $mail->addBcc($user->email);
                        }
                    }
                    
                    // SEND IT...
                    $mail->sendHtmlTemplate("order.close_tomorrow.tpl.php");
                }
            }
        }
    }
    
    
    
    
    
    private function _sendToAllUsers($email_ml, $idgroup, &$mail) {
        // check if email_ml EXISTS, if not it sends email to all users in the group!
        if($email_ml != "") {
            $mail->addTo($email_ml);
        } else {
            $groupObj = new Model_Db_Users();
            $ugObj = $groupObj->getUsersByIdGroup($idgroup, true);
            foreach($ugObj AS $ugVal) {
                $mail->addBcc($ugVal->email);
            }
        }
    }
}
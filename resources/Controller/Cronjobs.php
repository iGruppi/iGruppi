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
    }
    
    /**
     * Called by cronjobs EVERY HOUR
     * @return void
     */
    function everyHourAction() {
        $this->startorderAction();
    }
    
    /**
     * Called by cronjobs EVERY DAY
     * @return void
     */
    function everyDayAction() {
        $this->closeorderAction();
    }

    /**
     * Send email to users for every NEW order in the next hour
     * @return void
     */
    function startorderAction() 
    {
        // some usefull date
        $today = new DateTime();
        $next_hour = $today->add( new DateInterval("PT1H")); // ADD 1 HOUR
        $dateToCheck = $next_hour->format("Y-m-d H");
        
        // GET any ORDER that will be APERTO in the NEXT HOUR
        $db = Zend_Registry::get("db");
        $sth = $db->prepare("SELECT * FROM ordini WHERE DATE_FORMAT(data_inizio, '%Y-%m-%d %H')= :data");
        $sth->execute(array('data' => $dateToCheck));
        if($sth->rowCount() > 0) {
            // fetch alla records
            $listOrd = $sth->fetchAll(PDO::FETCH_OBJ);
            // create array of Ordini objects
            $cObj = new Model_Db_Categorie();
            $ordObj = new Model_Db_Ordini();
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
                        // get Group object
                        $group = $ordineObj->getGroupByIdGroup($idgroup);
                        
                        // send email ONLY if ordine is VISIBILE
                        if($group->getVisibile()->getBool()) {
                            
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
        }
    }
    
    /**
     * Send email to users for the order that will be CLOSED tomorrow
     * @return void
     */
    function closeorderAction() 
    {
        // some usefull date
        $today = new DateTime();
        $next_hour = $today->add( new DateInterval("P1D")); // ADD 1 DAY
        $dateToCheck = $next_hour->format("Y-m-d");
        
        // GET any ORDER that will be CLOSE TOMORROW
        $db = Zend_Registry::get("db");
        $sth = $db->prepare("SELECT * FROM ordini WHERE DATE_FORMAT(data_fine, '%Y-%m-%d')= :data");
        $sth->execute(array('data' => $dateToCheck));
        if($sth->rowCount() > 0) {
            // fetch alla records
            $listOrd = $sth->fetchAll(PDO::FETCH_OBJ);
            // create array of Ordini objects
            $cObj = new Model_Db_Categorie();
            $ordObj = new Model_Db_Ordini();
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
                        // get Group object
                        $group = $ordineObj->getGroupByIdGroup($idgroup);
                        
                        // send email ONLY if ordine is VISIBILE
                        if($group->getVisibile()->getBool()) {
                            
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
        }
    }
    
}
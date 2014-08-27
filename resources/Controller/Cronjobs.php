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
        $today = new Zend_Date();
        $tomorrow = $today->addDay(1);
        $this->_dateTomorrow = $tomorrow->toString(MyFw_Form_Filters_Date::_MYFORMAT_DATE_DB);
        
    }
    
    function everyday20Action() {
        $this->startorderAction();
        $this->closeorderAction();
    }

    function startorderAction() {
        
        // GET any ORDER that will be Aperto TOMORROW
        $orderObj = new Model_Ordini();
        $orders = $orderObj->getAllByDate($this->_dateTomorrow, "data_inizio");
        if(count($orders) > 0) {
            foreach ($orders as $key => $ordine) {
                
                $idgroup = $ordine->idgroup;
                $idproduttore = $ordine->idproduttore;

                // PREPARE EMAIL TO GROUP LIST
                $mail = new MyFw_Mail();
                $mail->setSubject("Nuovo ordine Aperto per ".$ordine->ragsoc);
                $mail->setViewParam("ordine", $ordine);
                
                // SET array Categorie prodotti for Produttore
                $catObj = new Model_Categorie();
                $arCat = $catObj->getCategories_withKeyIdProduttore();
                if(isset($arCat[$idproduttore]) && count($arCat[$idproduttore]) > 0) {
                    $mail->setViewParam("arCat", $arCat[$idproduttore]);
                }
                
                // send Email to All Users
                $this->sendToAllUsers($ordine->email_ml, $idgroup, $mail);
                // SEND IT...
                $mail->sendHtmlTemplate("order.start_open.tpl.php");
            }
        }
    }
    
    function closeorderAction() {
        // GET any ORDER that will be Chiuso TOMORROW
        $orderObj = new Model_Ordini();
        $orders = $orderObj->getAllByDate($this->_dateTomorrow, "data_fine");
        if(count($orders) > 0) {
            foreach ($orders as $key => $ordine) {
                
                $idgroup = $ordine->idgroup;

                // PREPARE EMAIL TO GROUP LIST
                $mail = new MyFw_Mail();
                $mail->setSubject("Chiusura ordine per ".$ordine->ragsoc);
                $mail->setViewParam("ordine", $ordine);
                // send Email to All Users
                $this->sendToAllUsers($ordine->email_ml, $idgroup, $mail);
                // SEND IT...
                $mail->sendHtmlTemplate("order.close_tomorrow.tpl.php");
                
            }
        }
    }
    
    
    
    
    
    private function sendToAllUsers($email_ml, $idgroup, &$mail) {
        // check if email_ml EXISTS, if not it sends email to all users in the group!
        if($email_ml != "") {
            $mail->addTo($email_ml);
        } else {
            $groupObj = new Model_Users();
            $ugObj = $groupObj->getUsersByIdGroup($idgroup);
            foreach($ugObj AS $ugVal) {
                $mail->addBcc($ugVal->email);
            }
        }
    }
}
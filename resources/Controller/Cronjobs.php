<?php
/**
 * Description of Cronjobs
 * 
 * @author gullo
 */
class Controller_Cronjobs extends MyFw_Controller {

    function _init() {
        $layout = Zend_Registry::get("layout");
        $layout->disableDisplay();
    }
    
    function everyday20Action() {
        $this->startorderAction();
    }
 
    function startorderAction() {
        // date to check
        $today = new Zend_Date();
        $tomorrow = $today->addDay(1);
        $date = $tomorrow->toString("YYYY-MM-dd");
        
        // GET GROUP/PRODUTTORE for any OPENED ORDER
        $orderObj = new Model_Ordini();
        $orders = $orderObj->getAllByDate($date, "data_inizio");
        if(count($orders) > 0) {
            foreach ($orders as $key => $ordine) {
                
                $idgroup = $ordine->idgroup;
                $idproduttore = $ordine->idproduttore;

                // PREPARE EMAIL TO GROUP LIST
                $mail = new MyFw_Mail();
                $mail->setSubject("Nuovo ordine aperto, Gruppo ".$ordine->nome);
                $mail->setViewParam("ordine", $ordine);
                
                // SET array Categorie prodotti for Produttore
                $catObj = new Model_Categorie();
                $arCat = $catObj->getSubCategoriesByIdgroup($idgroup);
                if(isset($arCat[$idproduttore]) && count($arCat[$idproduttore]) > 0) {
                    $mail->setViewParam("arCat", $arCat[$idproduttore]);
                }
                
                // check if email_ml EXISTS, if not it sends email to all users in the group!
                if($ordine->email_ml != "") {
                    $mail->addTo($ordine->email_ml);
                } else {
                    $groupObj = new Model_Users();
                    $ugObj = $groupObj->getUsersByIdGroup($idgroup);
                    foreach($ugObj AS $ugVal) {
                        $mail->addBcc($ugVal["email"]);
                    }
                }
                // SEND IT...
                $mail->sendHtmlTemplate("order.start_open.tpl.php");
            }
        }
    }
    
    
}
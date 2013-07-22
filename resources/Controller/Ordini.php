<?php
/**
 * Gestione Ordini per utenti
 *
 * @author gullo
 */
class Controller_Ordini extends MyFw_Controller {

    private $_userSessionVal;
    private $_iduser;
    
    function _init() {
        $auth = Zend_Auth::getInstance();
        $this->_iduser = $auth->getIdentity()->iduser;
        $this->_userSessionVal = new Zend_Session_Namespace('userSessionVal');
    }

    function indexAction() {
        
        $statusSelected = $this->getParam("st");
        if(is_null($statusSelected)) {
            $statusSelected = Model_Ordini_Status::STATUS_APERTO;
        }
        
        $ordiniObj = new Model_Ordini();
        $listOrd = $ordiniObj->getAllByIdgroup($this->_userSessionVal->idgroup);
        // add Status model to Ordini
        if(count($listOrd) > 0) {
            foreach($listOrd AS &$ordine) {
                $ordine->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);
            }
        }
        $this->view->list = $listOrd;
        //Zend_Debug::dump($listOrd);die;
    }


    function prodottiAction() {
        
        $idordine = $this->getParam("idordine");
        $ordObj = new Model_Ordini();
        $ordine = $ordObj->getByIdOrdine($idordine);
        $this->view->ordine = $ordine;
        $this->view->statusObj = new Model_Ordini_Status($ordine->data_inizio, $ordine->data_fine, $ordine->archiviato);;
        
        $produttoreObj = new Model_Produttori();
        $produttore = $produttoreObj->getProduttoreById($ordine->idproduttore, $this->_userSessionVal->idgroup);
        $this->view->produttore = $produttore;
        
        # TODO : Inserire controllo per i furbi che vogliono visualizzare/cancellare ordini non loro
        
        $this->view->updated = false;
        
        // SAVE FORM
        if($this->getRequest()->isPost()) {
            
            // get Post and check if is valid
            $fv = $this->getRequest()->getPost();
            
            $prod_qta = isset($fv["prod_qta"]) ? $fv["prod_qta"] : array();
            if(count($prod_qta) > 0) {
                // delete all records in ordini_user_prodotti
                $this->getDB()->query("DELETE FROM ordini_user_prodotti WHERE iduser='".$this->_iduser."' AND idordine='$idordine'");
                // insert product selected
                foreach ($prod_qta as $idprodotto => $qta) {
                    if( $qta > 0) {
                        // prepare SQL INSERT
                        $sql = "INSERT INTO ordini_user_prodotti SET iduser= :iduser, idprodotto= :idprodotto, idordine= :idordine, qta= :qta, data_ins=NOW()";
                        $sth = $this->getDB()->prepare($sql);
                        $fields = array('iduser' => $this->_iduser, 'idprodotto' => $idprodotto, 'idordine' => $idordine, 'qta' => $qta);
                        $sth->execute($fields);
                    }
                }
                
                $this->view->updated = true;
            }
        }

        // elenco prodotti (aggiornato dopo eventuale POST)
        $this->view->list = $ordObj->getProdottiByIdOrdine($idordine, $ordine->idproduttore, $this->_iduser);
        //Zend_Debug::dump($this->view->list);
    }

    function archivioAction() {
        
    }
}
?>

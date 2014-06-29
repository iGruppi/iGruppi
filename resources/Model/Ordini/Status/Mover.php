<?php
/**
 * Description of Model_Ordini_Status_Mover
 * 
 * @author gullo
 */
class Model_Ordini_Status_Mover {
    
    private $_ordine;
    
    public function __construct($ordine) 
    {
        $this->_ordine = $ordine;
    }
    
    public function moveToStatus($st)
    {
        switch ($st) {
            case Model_Ordini_Status::STATUS_CHIUSO:
                return $this->moveTo_Chiuso();

            case Model_Ordini_Status::STATUS_INCONSEGNA:
                return $this->moveTo_InConsegna();

            case Model_Ordini_Status::STATUS_CONSEGNATO:
                return $this->moveTo_Consegnato();

            case Model_Ordini_Status::STATUS_ARCHIVIATO:
                return $this->moveTo_Archiviato();
        }
        return false;
    }
    
    private function moveTo_Chiuso()
    {
        $db = Zend_Registry::get("db");
        $sth_update = $db->prepare("UPDATE ordini SET data_inconsegna=NULL, data_consegnato=NULL, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
    
    private function moveTo_InConsegna()
    {
        $dtNow = new Zend_Date();
        $db = Zend_Registry::get("db");
        $sth_update = $db->prepare("UPDATE ordini SET data_inconsegna= :dtNow, data_consegnato=NULL, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine, 'dtNow' => $dtNow->toString("yyyy-MM-dd HH:mm:ss")));
    }
    
    private function moveTo_Consegnato()
    {
        $dtNow = new Zend_Date();
        $db = Zend_Registry::get("db");
        $sth_update = $db->prepare("UPDATE ordini SET data_consegnato= :dtNow, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine, 'dtNow' => $dtNow->toString("yyyy-MM-dd HH:mm:ss")));
    }
    
    private function moveTo_Archiviato()
    {
        $db = Zend_Registry::get("db");
        $sth_update = $db->prepare("UPDATE ordini SET archiviato='S' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
}

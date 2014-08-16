<?php
/**
 * Description of Model_Ordini_Status_Mover
 * 
 * @author gullo
 */
class Model_Ordini_Status_Mover {
    
    private $_ordine;
    private $_db;
    private $_dtNow;
    
    public function __construct($ordine) 
    {
        $this->_ordine = $ordine;
        $this->_db = Zend_Registry::get("db");
        $this->_dtNow = new Zend_Date();
    }
    
    public function moveToStatus($st)
    {
        switch ($st) {
            case Model_Ordini_Status::STATUS_CHIUSO:
                return $this->moveTo_Chiuso();

            case Model_Ordini_Status::STATUS_INVIATO:
                return $this->moveTo_Inviato();

            case Model_Ordini_Status::STATUS_ARRIVATO:
                return $this->moveTo_Arrivato();

            case Model_Ordini_Status::STATUS_CONSEGNATO:
                return $this->moveTo_Consegnato();

            case Model_Ordini_Status::STATUS_ARCHIVIATO:
                return $this->moveTo_Archiviato();
        }
        return false;
    }
    
    private function moveTo_Chiuso()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_inviato=NULL, data_arrivato=NULL, data_consegnato=NULL, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
    
    private function moveTo_Inviato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_inviato= :dtNow, data_arrivato=NULL, data_consegnato=NULL, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine, 'dtNow' => $this->_dtNow->toString("yyyy-MM-dd HH:mm:ss")));
    }
    
    private function moveTo_Arrivato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_arrivato= :dtNow, data_consegnato=NULL, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine, 'dtNow' => $this->_dtNow->toString("yyyy-MM-dd HH:mm:ss")));
    }
    
    private function moveTo_Consegnato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_consegnato= :dtNow, archiviato='N' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine, 'dtNow' => $this->_dtNow->toString("yyyy-MM-dd HH:mm:ss")));
    }
    
    private function moveTo_Archiviato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET archiviato='S' WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
}

<?php
/**
 * Description of Model_Ordini_State_Mover
 * 
 * @author gullo
 */
class Model_Ordini_State_Mover {
    
    private $_ordine;
    private $_db;
    
    public function __construct($ordine) 
    {
        $this->_ordine = $ordine;
        $this->_db = Zend_Registry::get("db");
    }
    
    public function moveToStatus($st)
    {
        switch ($st) {
            case Model_Ordini_State_States_Chiuso::STATUS_NAME:
                return $this->moveTo_Chiuso();

            case Model_Ordini_State_States_Inviato::STATUS_NAME:
                return $this->moveTo_Inviato();

            case Model_Ordini_State_States_Arrivato::STATUS_NAME:
                return $this->moveTo_Arrivato();

            case Model_Ordini_State_States_Consegnato::STATUS_NAME:
                return $this->moveTo_Consegnato();

        }
        return false;
    }
    
    private function moveTo_Chiuso()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_inviato=NULL, data_arrivato=NULL, data_consegnato=NULL WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
    
    private function moveTo_Inviato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_inviato=NOW(), data_arrivato=NULL, data_consegnato=NULL WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
    
    private function moveTo_Arrivato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_arrivato=NOW(), data_consegnato=NULL WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }
    
    private function moveTo_Consegnato()
    {
        $sth_update = $this->_db->prepare("UPDATE ordini SET data_consegnato=NOW() WHERE idordine= :idordine");
        return $sth_update->execute(array('idordine' => $this->_ordine->idordine));
    }

}

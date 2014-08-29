<?php
/**
 * This is a PRODOTTO for ORDINI
 */
class Model_Builder_Prodotto_Parts_Ordine 
    extends Model_Builder_Prodotto_Parts_Listino
{

    /**
     * @return bool
     */    
    public function isDisponibile() {
        return $this->getDisponibileOrdine();
    }
    
    
    /**
     * @return array
     */    
    public function dumpValuesForDB()
    {
        $ar = array(
            'idlistino'         => $this->getIdListino(),
            'idgroup_master'    => $this->getIdGroupMaster(),
            'idgroup_slave'     => $this->getIdGroup(),
            'iduser_ref'        => $this->getRefIdUser(),
            'visibile'          => $this->getVisibile()->getString(),
            'note_consegna'     => $this->getNoteConsegna()
        );
        return $ar;
    }
    
}

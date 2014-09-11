<?php
/**
 * This is a PRODOTTO for ANAGRAFICA prodotti
 */
class Model_Builder_Prodotto_Parts_Anagrafica
    extends Model_Builder_Prodotto_Parts_Product

{
    /**
     * Anagrafica context
     * @var string
     */
    protected $_context = "Anagrafica";


    /**
     * @return string
     */    
    public function getDescrizioneCosto()
    {
        return number_format($this->getCosto(), 2, ",", ".") . " " . $this->getUdmDescrizione();
    }
    
    /**
     * @return string
     */    
    public function getDescrizionePezzatura()
    {
        $arUdm = Model_Prodotti_UdM::getArWithMultip();
        $pp = "";
        if( $this->hasPezzatura() ) {
            $pp .= round($this->getMoltiplicatore(), $arUdm[$this->getUdm()]["ndec"]) . " " . $arUdm[$this->getUdm()]["label"];
        }
        return $pp;
    }
    
    /**
     * @return bool
     */    
    public function hasPezzatura()
    {
        $arUdm = Model_Prodotti_UdM::getArWithMultip();
        return ( isset($arUdm[$this->getUdm()]) && $this->getMoltiplicatore() != 1 ) ? true : false;
    }
    
    /**
     * @return string
     */    
    public function getUdmDescrizione()
    {
        $fpz = ($this->hasPezzatura()) ? "**" : "";
        return "&euro; / " . $this->getUdm() . $fpz;
    }
        
    
    /**
     * @return bool
     */    
    public function hasIva() 
    {
        return ($this->getIva() > 0);
    }

    /**
     * 
     * @return array
     */    
    public function dumpValuesForDB() { 
        
        return array();
    }
    
}
